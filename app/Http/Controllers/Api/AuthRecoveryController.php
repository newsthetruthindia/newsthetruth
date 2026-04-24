<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthRecoveryController extends Controller
{
    /**
     * Resend verification email.
     */
    public function resendVerification(Request $request)
    {
        // Simple form to ask for email
        if ($request->isMethod('get')) {
            return response('
                <div style="font-family:sans-serif; max-width:400px; margin:100px auto; padding:20px; border:1px solid #eee; border-radius:8px;">
                    <h2 style="color:#8c0000">Resend Verification</h2>
                    <p style="font-size:13px; color:#666">Enter your admin email to receive a fresh verification link.</p>
                    <form method="POST">
                        '.csrf_field().'
                        <input type="email" name="email" placeholder="email@newsthetruth.com" style="width:100%; padding:10px; margin:10px 0; border:1px solid #ddd;" required>
                        <button type="submit" style="background:#8c0000; color:white; border:none; padding:10px 20px; cursor:pointer;">Send Link</button>
                    </form>
                    <p style="margin-top:20px"><a href="/admin/login" style="font-size:12px; color:#999">Back to Login</a></p>
                </div>
            ');
        }

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->sendEmailVerificationNotification();
        }

        return response('
             <div style="font-family:sans-serif; max-width:400px; margin:100px auto; text-align:center;">
                <h3>Email Sent</h3>
                <p>If that email exists in our system, a verification link has been sent. Please check your Inbox and Spam.</p>
                <a href="/admin/login">Back to Login</a>
            </div>
        ');
    }

    /**
     * Request a 2FA reset link.
     */
    public function reset2faRequest(Request $request)
    {
        if ($request->isMethod('get')) {
            return response('
                <div style="font-family:sans-serif; max-width:400px; margin:100px auto; padding:20px; border:1px solid #eee; border-radius:8px;">
                    <h2 style="color:#111">2FA Recovery</h2>
                    <p style="font-size:13px; color:#666">Lost your authenticator app? Enter your admin email to receive a 2FA reset link.</p>
                    <form method="POST">
                        '.csrf_field().'
                        <input type="email" name="email" placeholder="admin@newsthetruth.com" style="width:100%; padding:10px; margin:10px 0; border:1px solid #ddd;" required>
                        <button type="submit" style="background:#111; color:white; border:none; padding:10px 20px; cursor:pointer;">Send Reset Link</button>
                    </form>
                    <p style="margin-top:20px"><a href="/admin/login" style="font-size:12px; color:#999">Back to Login</a></p>
                </div>
            ');
        }

        $user = User::where('email', $request->email)->where('type', 'admin')->first();
        
        if ($user && $user->google2fa_secret) {
            $token = Str::random(64);
            DB::table('password_resets')->updateOrInsert(
                ['email' => $user->email],
                ['token' => $token, 'created_at' => now()]
            );

            $resetUrl = url("/auth/reset-2fa-execute?token={$token}&email=" . urlencode($user->email));

            Mail::send([], [], function ($message) use ($user, $resetUrl) {
                $message->to($user->email)
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('SECURE: 2FA Reset Link for NTT Admin')
                    ->html("
                        <div style='font-family:\"Helvetica Neue\",Helvetica,Arial,sans-serif;max-width:600px;margin:0 auto;padding:40px 20px;background-color:#ffffff;border:4px solid #8c0000;border-radius:16px;'>
                            <div style='text-align:center;margin-bottom:32px;'>
                                <h1 style='font-size:32px;font-weight:900;color:#8c0000;margin:0;letter-spacing:-1px;'>NEWS THE TRUTH</h1>
                                <p style='font-size:10px;font-weight:bold;color:#111827;text-transform:uppercase;letter-spacing:2px;margin-top:4px;'>Administrative Security</p>
                            </div>
                            <div style='padding:0 20px;'>
                                <h2 style='font-size:22px;color:#111827;margin-bottom:16px;font-weight:bold;'>2FA Recovery Request</h2>
                                <p style='color:#111827;font-size:16px;line-height:1.6;margin-bottom:24px;'>
                                    We received a request to disable Two-Factor Authentication for your administrative account.
                                </p>
                                <div style='background-color:#fff5f5;border-left:4px solid #8c0000;padding:16px;margin-bottom:32px;'>
                                    <p style='color:#8c0000;font-size:14px;font-weight:bold;margin:0;'>SECURITY WARNING:</p>
                                    <p style='color:#8c0000;font-size:14px;margin:8px 0 0;'>Only click the button below if you requested this. This link will expire in 60 minutes.</p>
                                </div>
                                <div style='text-align:center;margin-bottom:32px;'>
                                    <a href='{$resetUrl}' style='display:inline-block;background:#8c0000;color:#ffffff;padding:16px 40px;text-decoration:none;border-radius:12px;font-weight:bold;font-size:16px;box-shadow:0 10px 15px -3px rgba(140, 0, 0, 0.2);'>DISABLE 2FA NOW</a>
                                </div>
                                <p style='color:#9ca3af;font-size:12px;margin-top:32px;line-height:1.5;text-align:center;'>
                                    If you did not request this recovery link, please contact the system administrator immediately.
                                </p>
                            </div>
                        </div>
                    ");
            });
        }

        return response('
            <div style="font-family:sans-serif; max-width:400px; margin:100px auto; text-align:center;">
                <h3>Rescue Email Sent</h3>
                <p>Check your email for instructions to reset your 2FA.</p>
                <a href="/admin/login">Back to Login</a>
            </div>
        ');
    }

    /**
     * Execute the 2FA reset.
     */
    public function reset2faExecute(Request $request)
    {
        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$reset || \Carbon\Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
            return response('<h3>Link Expired or Invalid</h3><a href="/admin/login">Back</a>');
        }

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->google2fa_secret = null;
            $user->save();
            
            DB::table('password_resets')->where('email', $request->email)->delete();
        }

        return response('
            <div style="font-family:sans-serif; max-width:400px; margin:100px auto; text-align:center; border:2px solid green; padding:20px;">
                <h2 style="color:green">2FA Disabled Successfully</h2>
                <p>You can now log in using just your email and password.</p>
                <p>Please re-enable 2FA in your profile settings once you are logged in.</p>
                <a href="/admin/login" style="display:inline-block; background:green; color:white; padding:10px 20px; text-decoration:none; border-radius:4px;">Login Now</a>
            </div>
        ');
    }
}
