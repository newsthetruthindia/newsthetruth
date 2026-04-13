<?php
// verify_subtitle_field.php
// Run this on the VPS: php verify_subtitle_field.php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== DATABASE SCHEMA VERIFICATION ===\n";

$tables = ['posts', 'citizen_journalisms'];

foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        if (Schema::hasColumn($table, 'subtitle')) {
            echo "[SUCCESS] Table '{$table}' already has 'subtitle' column.\n";
        } else {
            echo "[MISSING] Table '{$table}' is MISSING 'subtitle' column.\n";
            echo "Attempting to fix...\n";
            try {
                DB::statement("ALTER TABLE {$table} ADD COLUMN subtitle VARCHAR(500) NULL AFTER slug");
                echo "[FIXED] Added 'subtitle' column to '{$table}'.\n";
            } catch (\Exception $e) {
                echo "[ERROR] Failed to add column: " . $e->getMessage() . "\n";
            }
        }
    } else {
        echo "[WARNING] Table '{$table}' does not exist.\n";
    }
}

echo "=== VERIFICATION COMPLETE ===\n";
