<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ApiAuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'type'      => 'user',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        // Send verification email after registration
        $this->sendVerificationEmail($user);

        return response()->json([
            'message'      => 'Successfully registered',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user->only(['id', 'firstname', 'lastname', 'email', 'type']),
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login credentials'], 401);
        }

        $user  = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'      => 'Successfully logged in',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            // SECURITY: Only return safe public fields — never return password, 2FA secrets, salary, OTPs
            'user'         => $user->only(['id', 'firstname', 'lastname', 'email', 'type', 'email_verified_at']),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        // SECURITY FIX (HIGH-4): Always return the same message regardless of whether
        // the user exists. This prevents user enumeration attacks.
        if (!$user) {
            return response()->json(['message' => 'If that email is registered, a reset link will be sent.']);
        }

        $token = Str::random(60);

        // SECURITY FIX (HIGH-3): Store password reset token as a HASH, never plain text.
        // The raw token is sent in the email; only the hash is stored in the DB.
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        $resetUrl = env('FRONTEND_URL', 'https://newsthetruth.com') . '/reset-password?token=' . $token . '&email=' . urlencode($request->email);

        try {
            Mail::send([], [], function ($message) use ($user, $resetUrl) {
                $message->to($user->email)
                    ->subject('Reset Your NTT Password')
                    ->html("
                        <div style='font-family:Arial,sans-serif;max-width:600px;margin:0 auto;padding:40px 20px;'>
                            <h1 style='font-size:28px;font-weight:900;color:#111827;margin-bottom:8px;'>News The Truth</h1>
                            <hr style='border:none;border-top:3px solid #8c0000;margin:16px 0 32px;width:60px;'>
                            <h2 style='font-size:20px;color:#111827;margin-bottom:16px;'>Password Reset Request</h2>
                            <p style='color:#4b5563;font-size:15px;line-height:1.6;margin-bottom:24px;'>
                                Hi {$user->firstname}, we received a request to reset your password. Click the button below to create a new one:
                            </p>
                            <a href='{$resetUrl}' style='display:inline-block;background:#8c0000;color:white;padding:14px 32px;text-decoration:none;border-radius:8px;font-weight:bold;font-size:14px;'>Reset Password</a>
                            <p style='color:#9ca3af;font-size:12px;margin-top:32px;line-height:1.5;'>
                                This link expires in 60 minutes. If you didn't request this, please ignore this email.
                            </p>
                        </div>
                    ");
            });
        } catch (\Exception $e) {
            // Log internally but don't expose error to prevent information leakage
            \Log::error('Password reset email failed: ' . $e->getMessage());
        }

        // SECURITY: Always return success to prevent user enumeration
        return response()->json(['message' => 'If that email is registered, a reset link will be sent.']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->first();

        // SECURITY FIX (HIGH-3): Compare against hashed token stored in DB
        if (!$reset || !Hash::check($request->token, $reset->token) || Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
            return response()->json(['message' => 'Invalid or expired token'], 400);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'Invalid or expired token'], 400);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Invalidate all existing tokens for security
        $user->tokens()->delete();

        DB::table('password_resets')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Password reset successfully']);
    }

    /**
     * Send email verification link to a user.
     */
    private function sendVerificationEmail(User $user)
    {
        $token = Str::random(60);

        DB::table('email_verifications')->updateOrInsert(
            ['email' => $user->email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        $verifyUrl = env('FRONTEND_URL', 'https://newsthetruth.com') . '/verify-email?token=' . $token . '&email=' . urlencode($user->email);

        try {
            Mail::send([], [], function ($message) use ($user, $verifyUrl) {
                $message->to($user->email)
                    ->subject('Verify Your NTT Email')
                    ->html("
                        <div style='font-family:Arial,sans-serif;max-width:600px;margin:0 auto;padding:40px 20px;'>
                            <h1 style='font-size:28px;font-weight:900;color:#111827;margin-bottom:8px;'>News The Truth</h1>
                            <hr style='border:none;border-top:3px solid #8c0000;margin:16px 0 32px;width:60px;'>
                            <h2 style='font-size:20px;color:#111827;margin-bottom:16px;'>Verify Your Email</h2>
                            <p style='color:#4b5563;font-size:15px;line-height:1.6;margin-bottom:24px;'>
                                Hi {$user->firstname}, please verify your email address by clicking the button below:
                            </p>
                            <a href='{$verifyUrl}' style='display:inline-block;background:#8c0000;color:white;padding:14px 32px;text-decoration:none;border-radius:8px;font-weight:bold;font-size:14px;'>Verify Email</a>
                            <p style='color:#9ca3af;font-size:12px;margin-top:32px;line-height:1.5;'>
                                This link expires in 24 hours. If you didn't create an account, please ignore this email.
                            </p>
                        </div>
                    ");
            });
        } catch (\Exception $e) {
            \Log::error('Verification email failed: ' . $e->getMessage());
        }
    }

    /**
     * Resend verification email (authenticated user).
     */
    public function resendVerification(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($user->email_verified_at) {
            return response()->json(['message' => 'Email is already verified.']);
        }

        $this->sendVerificationEmail($user);

        return response()->json(['message' => 'Verification email sent successfully.']);
    }

    /**
     * Verify email via token link.
     */
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
        ]);

        $record = DB::table('email_verifications')
            ->where('email', $request->email)
            ->first();

        if (!$record || !Hash::check($request->token, $record->token) || Carbon::parse($record->created_at)->addHours(24)->isPast()) {
            return response()->json(['message' => 'Invalid or expired verification link.'], 400);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $user->email_verified_at = now();
        $user->save();

        DB::table('email_verifications')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Email verified successfully!']);
    }
}
