<?php
// Simulate calling the forgot-password endpoint internally
require '/var/www/ntt/vendor/autoload.php';
$app = require_once '/var/www/ntt/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Simulating forgotPassword call ===" . PHP_EOL;
echo "FRONTEND_URL: " . env('FRONTEND_URL', 'NOT SET') . PHP_EOL;

$email = 'newsthetruthindia@gmail.com';
$user = \App\Models\User::where('email', $email)->first();
if (!$user) { die("USER NOT FOUND!\n"); }
echo "User: " . $user->firstname . " " . $user->email . " (" . $user->type . ")" . PHP_EOL;

// Generate token
$token = \Illuminate\Support\Str::random(60);
\Illuminate\Support\Facades\DB::table('password_resets')->updateOrInsert(
    ['email' => $email],
    ['token' => $token, 'created_at' => now()]
);
echo "Token saved" . PHP_EOL;

$resetUrl = env('FRONTEND_URL', 'https://newsthetruth.com') . '/reset-password?token=' . $token . '&email=' . $email;
echo "Reset URL: " . $resetUrl . PHP_EOL;

// Send email using the IMPROVED template
try {
    \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($user, $resetUrl) {
        $message->to($user->email)
            ->subject('Reset Your NTT Password')
            ->html("
                <div style='font-family:Arial,sans-serif;max-width:600px;margin:0 auto;padding:40px 20px;'>
                    <h1 style='font-size:28px;font-weight:900;color:#111827;margin-bottom:8px;'>News The Truth</h1>
                    <hr style='border:none;border-top:3px solid #8c0000;margin:16px 0 32px;width:60px;'>
                    <h2 style='font-size:20px;color:#111827;margin-bottom:16px;'>Password Reset Request</h2>
                    <p style='color:#4b5563;font-size:15px;line-height:1.6;margin-bottom:24px;'>
                        Hi {$user->firstname}, we received a request to reset your password. Click the button below:
                    </p>
                    <a href='{$resetUrl}' style='display:inline-block;background:#8c0000;color:white;padding:14px 32px;text-decoration:none;border-radius:8px;font-weight:bold;font-size:14px;'>Reset Password</a>
                    <p style='color:#9ca3af;font-size:12px;margin-top:32px;line-height:1.5;'>
                        This link expires in 60 minutes. If you didn't request this, ignore this email.
                    </p>
                </div>
            ");
    });
    echo "SUCCESS: Email sent to " . $email . PHP_EOL;
} catch (Exception $e) {
    echo "SMTP ERROR: " . $e->getMessage() . PHP_EOL;
    echo "SMTP TRACE: " . $e->getTraceAsString() . PHP_EOL;
}
