<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$creds = [
    'fb_app_id' => '841173934997961',
    'fb_app_secret' => '68419cb751d6d69ab213652ca4da8c62',
    'fb_page_id' => '115000625006655',
    'fb_access_token' => 'EAAL9CxGXAckBRbsiMkqozG2kIw8BRF8sKeH2KrCtFLmTZCizwuyhdEFQhIcqvYX8CYvUHKRv0FL2k2INSFZAQ0gCLiyf43QD9i1oa5yeZBis6wpdyaQB0UKSl9a5Dzqls3UzXpe4vfbDsHEKrDSTUJW7YVq41ytcMnUBBo9dtbnag8gIbRZCH5bonQv3kcNHCGse8kBaJriNRWEObZCfNaSKXgsRyuzutPQWoM9WO0zoMicrGxzZCQaKI0cZAn12xr8ksT0ZCyAWbN2jqwmYtze5',
];

foreach ($creds as $key => $value) {
    App\Models\Option::updateOrCreate(['key' => $key], ['value' => $value]);
}

$service = new App\Services\SocialPublishingService();
$result = $service->exchangeForPageToken($creds['fb_access_token'], $creds['fb_app_id'], $creds['fb_app_secret'], $creds['fb_page_id']);

echo "Result: ";
print_r($result);
