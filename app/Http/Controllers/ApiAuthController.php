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

use Illuminate\Support\Facades\Http;

class ApiAuthController extends Controller
{
    public function googleLogin(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'redirect_uri' => 'required|string',
        ]);

        try {
            // 1. Exchange code for access token
            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'code' => $request->code,
                'client_id' => config('services.google.client_id'),
                'client_secret' => config('services.google.client_secret'),
                'redirect_uri' => $request->redirect_uri,
                'grant_type' => 'authorization_code',
            ]);

            if ($response->failed()) {
                \Log::error('Google Token Exchange Failed', ['response' => $response->json()]);
                return response()->json(['message' => 'Failed to exchange Google code.'], 400);
            }

            $accessToken = $response->json('access_token');

            // 2. Fetch user information from Google
            $userResponse = Http::withToken($accessToken)->get('https://www.googleapis.com/oauth2/v3/userinfo');

            if ($userResponse->failed()) {
                return response()->json(['message' => 'Failed to fetch user info from Google.'], 400);
            }

            $googleUser = $userResponse->json();
            $email = $googleUser['email'];

            // 3. Find or create user
            $user = User::where('google_id', $googleUser['sub'])
                ->orWhere('email', $email)
                ->first();

            if (!$user) {
                $user = User::create([
                    'firstname' => $googleUser['given_name'] ?? 'Google',
                    'lastname'  => $googleUser['family_name'] ?? 'User',
                    'email'     => $email,
                    'google_id' => $googleUser['sub'],
                    'password'  => Hash::make(Str::random(24)), // Random password for social logins
                    'type'      => 'user',
                ]);
                $user->email_verified_at = now();
                $user->save();
            } else {
                // Update google_id if it was a pre-existing email-only account
                if (empty($user->google_id)) {
                    $user->google_id = $googleUser['sub'];
                    $user->save();
                }
            }

            // 4. Generate Sanctum token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message'      => 'Successfully logged in with Google',
                'access_token' => $token,
                'token_type'   => 'Bearer',
                'user'         => $user->only(['id', 'firstname', 'lastname', 'email', 'type', 'email_verified_at']),
            ]);

        } catch (\Exception $e) {
            \Log::error('Google Login Error: ' . $e->getMessage());
            return response()->json(['message' => 'Internal server error during Google login.'], 500);
        }
    }
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
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('Reset Your NTT Password')
                    ->html("
                        <div style='font-family:\"Helvetica Neue\",Helvetica,Arial,sans-serif;max-width:600px;margin:0 auto;padding:40px 20px;background-color:#ffffff;border:1px solid #e5e7eb;border-radius:16px;'>
                            <div style='text-align:center;margin-bottom:32px;'>
                                <h1 style='font-size:32px;font-weight:900;color:#8c0000;margin:0;letter-spacing:-1px;'>NEWS THE TRUTH</h1>
                                <p style='font-size:10px;font-weight:bold;color:#9ca3af;text-transform:uppercase;letter-spacing:2px;margin-top:4px;'>Gatekeeper Access</p>
                            </div>
                            <div style='padding:0 20px;'>
                                <h2 style='font-size:22px;color:#111827;margin-bottom:16px;font-weight:bold;'>Password Reset Request</h2>
                                <p style='color:#4b5563;font-size:16px;line-height:1.6;margin-bottom:32px;'>
                                    Hi {$user->firstname}, we received a request to reset your password. Click the button below to authorize this change:
                                </p>
                                <div style='text-align:center;margin-bottom:32px;'>
                                    <a href='{$resetUrl}' style='display:inline-block;background:#8c0000;color:#ffffff;padding:16px 40px;text-decoration:none;border-radius:12px;font-weight:bold;font-size:16px;box-shadow:0 10px 15px -3px rgba(140, 0, 0, 0.2);'>Reset Password Now</a>
                                </div>
                                <p style='color:#9ca3af;font-size:13px;margin-top:32px;line-height:1.5;text-align:center;'>
                                    This secure link expires in 60 minutes. If you didn't request this, please secure your account immediately.
                                </p>
                            </div>
                            <div style='margin-top:40px;padding-top:20px;border-top:1px solid #f3f4f6;text-align:center;'>
                                <p style='color:#9ca3af;font-size:11px;'>&copy; " . date('Y') . " News The Truth. All rights reserved.</p>
                            </div>
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
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('Verify Your NTT Email')
                    ->html("
                        <div style='font-family:\"Helvetica Neue\",Helvetica,Arial,sans-serif;max-width:600px;margin:0 auto;padding:40px 20px;background-color:#ffffff;border:1px solid #e5e7eb;border-radius:16px;'>
                            <div style='text-align:center;margin-bottom:32px;'>
                                <h1 style='font-size:32px;font-weight:900;color:#8c0000;margin:0;letter-spacing:-1px;'>NEWS THE TRUTH</h1>
                                <p style='font-size:10px;font-weight:bold;color:#9ca3af;text-transform:uppercase;letter-spacing:2px;margin-top:4px;'>Account Verification</p>
                            </div>
                            <div style='padding:0 20px;'>
                                <h2 style='font-size:22px;color:#111827;margin-bottom:16px;font-weight:bold;'>Verify Your Identity</h2>
                                <p style='color:#4b5563;font-size:16px;line-height:1.6;margin-bottom:32px;'>
                                    Hi {$user->firstname}, welcome to News The Truth. To complete your registration and access the portal, please verify your email address:
                                </p>
                                <div style='text-align:center;margin-bottom:32px;'>
                                    <a href='{$verifyUrl}' style='display:inline-block;background:#8c0000;color:#ffffff;padding:16px 40px;text-decoration:none;border-radius:12px;font-weight:bold;font-size:16px;box-shadow:0 10px 15px -3px rgba(140, 0, 0, 0.2);'>Verify Email Now</a>
                                </div>
                                <p style='color:#9ca3af;font-size:13px;margin-top:32px;line-height:1.5;text-align:center;'>
                                    This link expires in 24 hours. If you did not create an account with us, please ignore this email.
                                </p>
                            </div>
                            <div style='margin-top:40px;padding-top:20px;border-top:1px solid #f3f4f6;text-align:center;'>
                                <p style='color:#9ca3af;font-size:11px;'>&copy; " . date('Y') . " News The Truth. All rights reserved.</p>
                            </div>
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
