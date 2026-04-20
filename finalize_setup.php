<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$token = 'EAAL9CxGXAckBRba118zRn6NAjNwgFWH8Ht11F2I1uH4diC9dZBmJZCojiMPV1KL7uhGN63xxVlLcfcErU9wMJOkcxC2yyJbBQLIdgOqMvytdDAh98s8tEbM4vptZBxwHd7vt2Pq2dnYZAImf20o6Mr5Ot0NwnlNMJxqYvJ6LxSqxK0ZCZAXZBv9ug8lRONfI698nb5F7oVHoZCGtZBrDhZCYOvPZC9gPsgUttzZBDLPj7Po9MrPZBVNhiPuplWgwu54AZClvOsrXIhKTKiiboODZC7aZA4QFLBioMpYZD';
$page_id = '115000625006655';
$ig_id = '17841460658517747';

\Illuminate\Support\Facades\DB::table('options')->updateOrInsert(['key' => 'fb_access_token'], ['value' => $token]);
\Illuminate\Support\Facades\DB::table('options')->updateOrInsert(['key' => 'fb_page_id'], ['value' => $page_id]);
\Illuminate\Support\Facades\DB::table('options')->updateOrInsert(['key' => 'ig_account_id'], ['value' => $ig_id]);

echo "SUCCESS\n";
