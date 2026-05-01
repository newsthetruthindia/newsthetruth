<?php
// setup_engagement.php
// This script will be pushed via Git and called via browser to finalize the setup.

// Security: Check for a secret token to prevent unauthorized access
if (($_GET['token'] ?? '') !== 'ntt_final_fix_2026') {
    die('Unauthorized');
}

echo "<pre>🚀 Starting final setup...\n";

// 1. Update .env file
$envPath = __DIR__ . '/../.env';
if (file_exists($envPath)) {
    $envContent = file_get_contents($envPath);
    
    $appId = '20c9c62e-30f4-42b1-8385-61444873a1ad';
    $apiKey = 'os_v2_app_ede4m1rq6rblda4fmfceq45bvu3o2os7wl4u6suaaolwoju2z33fvnnyivjeltr7hnwmwub2zj3b4w6qwrj7xy2lctbcrhfkfw54p3a';

    // Update ONESIGNAL_APP_ID
    if (strpos($envContent, 'ONESIGNAL_APP_ID=') !== false) {
        $envContent = preg_replace('/ONESIGNAL_APP_ID=.*/', 'ONESIGNAL_APP_ID=' . $appId, $envContent);
    } else {
        $envContent .= "\nONESIGNAL_APP_ID=" . $appId;
    }

    // Update ONESIGNAL_REST_API_KEY
    if (strpos($envContent, 'ONESIGNAL_REST_API_KEY=') !== false) {
        $envContent = preg_replace('/ONESIGNAL_REST_API_KEY=.*/', 'ONESIGNAL_REST_API_KEY=' . $apiKey, $envContent);
    } else {
        $envContent .= "\nONESIGNAL_REST_API_KEY=" . $apiKey;
    }

    if (file_put_contents($envPath, $envContent)) {
        echo "✅ .env updated with OneSignal keys.\n";
    } else {
        echo "❌ Failed to update .env.\n";
    }
} else {
    echo "❌ .env file not found at $envPath\n";
}

// 2. Run Migrations
echo "📦 Running database migrations...\n";
$output = [];
$return_var = 0;
exec('php ../artisan migrate --force 2>&1', $output, $return_var);
echo implode("\n", $output) . "\n";

if ($return_var === 0) {
    echo "✅ Migrations completed successfully.\n";
} else {
    echo "❌ Migration error.\n";
}

// 3. Clear Cache
echo "🧹 Clearing caches...\n";
exec('php ../artisan config:clear', $output);
exec('php ../artisan cache:clear', $output);
echo "✅ Caches cleared.\n";

echo "\n✨ ALL DONE! OneSignal and Polls should be working now.\n";
echo "⚠️  PLEASE DELETE THIS FILE FROM THE SERVER NOW!</pre>";
?>
