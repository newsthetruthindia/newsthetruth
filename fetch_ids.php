<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Http;

// Mocking some Laravel environment to use Http
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$token = 'EAAL9CxGXAckBRba118zRn6NAjNwgFWH8Ht11F2I1uH4diC9dZBmJZCojiMPV1KL7uhGN63xxVlLcfcErU9wMJOkcxC2yyJbBQLIdgOqMvytdDAh98s8tEbM4vptZBxwHd7vt2Pq2dnYZAImf20o6Mr5Ot0NwnlNMJxqYvJ6LxSqxK0ZCZAXZBv9ug8lRONfI698nb5F7oVHoZCGtZBrDhZCYOvPZC9gPsgUttzZBDLPj7Po9MrPZBVNhiPuplWgwu54AZClvOsrXIhKTKiiboODZC7aZA4QFLBioMpYZD';

echo "Fetching Page Details...\n";
$response = Http::get("https://graph.facebook.com/v19.0/me?fields=id,name,instagram_business_account&access_token=$token");

if ($response->successful()) {
    echo "SUCCESS:\n";
    print_r($response->json());
} else {
    echo "ERROR:\n";
    echo $response->body() . "\n";
}
