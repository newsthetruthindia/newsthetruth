<?php
require '/var/www/ntt/vendor/autoload.php';
$app = require_once '/var/www/ntt/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "FRONTEND_URL now: " . env('FRONTEND_URL', 'NOT SET') . PHP_EOL;

// Simulate the forgotPassword controller
$email = 'newsthetruthindia@gmail.com';
$user = \App\Models\User::where('email', $email)->first();
if (!$user) { die("User not found\n"); }
echo "User: " . $user->firstname . PHP_EOL;

$token = \Illuminate\Support\Str::random(60);
\Illuminate\Support\Facades\DB::table('password_resets')->updateOrInsert(
    ['email' => $email],
    ['token' => $token, 'created_at' => now()]
);
echo "Token stored in password_resets" . PHP_EOL;

$resetUrl = env('FRONTEND_URL', 'https://newsthetruth.com') . '/reset-password?token=' . $token . '&email=' . $email;
echo "Reset URL: " . $resetUrl . PHP_EOL;

try {
    \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($user, $resetUrl) {
        $message->to($user->email)
            ->subject('Password Reset Request')
            ->html("<h2>Password Reset</h2><p>Click below to reset your NTT password:</p><a href='{$resetUrl}' style='background:#8c0000;color:white;padding:12px 24px;text-decoration:none;border-radius:8px'>Reset Password</a><p style='margin-top:20px;color:#666;font-size:12px'>This link expires in 60 minutes.</p>");
    });
    echo 'SUCCESS: Reset email sent to ' . $email . PHP_EOL;
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
}
