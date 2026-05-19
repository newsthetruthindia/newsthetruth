# Backend Security Patch — Deployment Instructions
# Run these commands via SSH on the VPS

# --- 1. Replace the controllers ---
cp /var/www/html/app/Http/Controllers/ApiController.php /var/www/html/app/Http/Controllers/ApiController.php.bak
cp /var/www/html/app/Http/Controllers/ApiAuthController.php /var/www/html/app/Http/Controllers/ApiAuthController.php.bak

# (Upload ApiController_SECURE.php and ApiAuthController_SECURE.php via SFTP first, then:)
# cp ApiController_SECURE.php /var/www/html/app/Http/Controllers/ApiController.php
# cp ApiAuthController_SECURE.php /var/www/html/app/Http/Controllers/ApiAuthController.php

# --- 2. Fix CORS (HIGH-5) ---
# Edit /var/www/html/config/cors.php and change:
#   'allowed_origins' => ['*'],
# to:
#   'allowed_origins' => ['https://newsthetruth.com', 'https://www.newsthetruth.com'],

# --- 3. Fix Sanctum token expiry (HIGH-1) ---
# Edit /var/www/html/config/sanctum.php and change:
#   'expiration' => null,
# to:
#   'expiration' => 10080,  // 7 days in minutes

# --- 4. Fix APP_KEY hardcoded fallback (MED-2) ---
# Edit /var/www/html/config/app.php line 17 and change:
#   'key' => env('APP_KEY') ?: 'base64:hLTcKfxjb1sV/WlomwIB5IU8pmdqA4SNJu2fwlUb2jI=',
# to:
#   'key' => env('APP_KEY'),

# --- 5. Add auth:sanctum to /api/user/{id} route (CRIT-1) ---
# Edit /var/www/html/routes/api.php and change:
#   Route::get('/user/{id}', [ApiController::class, 'user']);
# to:
#   Route::middleware('auth:sanctum')->get('/user/{id}', [ApiController::class, 'user']);

# --- 6. Add tighter throttle to auth routes (MED-1) ---
# Edit /var/www/html/routes/api.php auth section to:
#   Route::middleware('throttle:5,1')->group(function () {
#       Route::post('/auth/login', [ApiAuthController::class, 'login']);
#       Route::post('/auth/forgot-password', [ApiAuthController::class, 'forgotPassword']);
#   });
#   Route::post('/auth/register', [ApiAuthController::class, 'register']);
#   Route::post('/auth/reset-password', [ApiAuthController::class, 'resetPassword']);

# --- 7. Clear all caches ---
php /var/www/html/artisan config:clear
php /var/www/html/artisan route:clear
php /var/www/html/artisan cache:clear
php /var/www/html/artisan optimize
