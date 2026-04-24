<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\PublicPageController_v1;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth Recovery Routes
Route::match(['get', 'post'], '/auth/resend-verification', [\App\Http\Controllers\Api\AuthRecoveryController::class, 'resendVerification']);
Route::match(['get', 'post'], '/auth/reset-2fa-request', [\App\Http\Controllers\Api\AuthRecoveryController::class, 'reset2faRequest']);
Route::get('/auth/reset-2fa-execute', [\App\Http\Controllers\Api\AuthRecoveryController::class, 'reset2faExecute']);


Route::get('/', [App\Http\Controllers\PublicPageController_v1::class, 'index'])->name('v1.home');
Route::get('clear-cache', function () {
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
});
// Route::get('v1/{x}', [App\Http\Controllers\PublicPageController_v1::class, 'handleRoute'])
//     ->where('x', '.*')
//     ->name('public.page');


// Route::get('/', [App\Http\Controllers\PublicPageController::class, 'index'])->name('home');
Route::get('401', [App\Http\Controllers\PublicPageController::class, 'unAuthorized'])->name('unauthorized');
Route::get('404', [App\Http\Controllers\PublicPageController::class, 'notFound'])->name('not-found');
Route::get('/archive', [App\Http\Controllers\PublicPageController::class, 'archive'])->name('archive');
Route::get('/latest', [App\Http\Controllers\PublicPageController::class, 'latest'])->name('latest');
Route::get('/mail/control', [App\Http\Controllers\HomeController::class, 'mailVerify'])->name('mail-control');
Route::get('/mail/verification', [App\Http\Controllers\HomeController::class, 'mailSend'])->name('mail-verification');

Route::get('/cron', [App\Http\Controllers\HomeController::class, 'cron'])->name('cron');
Auth::routes();

Route::get('/home', [App\Http\Controllers\PublicPageController::class, 'index'])->name('home');
Route::get('userlogout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('userlogout');
/*
* Medial and File related routs.
* Make this module public so that end user can upload medias.
*/
Route::get('/media/list', [App\Http\Controllers\MediaController::class, 'list'])->name('medias')->middleware(['auth', '2fa', 'admin']);
Route::get('/media/list/json/{alp?}', [App\Http\Controllers\MediaController::class, 'listJson'])->name('media-json');
Route::get('/media/add', [App\Http\Controllers\MediaController::class, 'add'])->name('add-media');
Route::post('/media/regenerate', [App\Http\Controllers\MediaController::class, 'regenerate'])->name('regenerate');
Route::get('/media/edit/{media}', [App\Http\Controllers\MediaController::class, 'edit'])->name('media-edit');
Route::post('/media/save', [App\Http\Controllers\MediaController::class, 'save'])->name('save-media');
Route::post('/media/savejson', [App\Http\Controllers\MediaController::class, 'savejson'])->name('save-media-json');
Route::get('/media/delete/{media}', [App\Http\Controllers\MediaController::class, 'delete'])->name('media-delete');
Route::get('/media/generation/reset', [App\Http\Controllers\MediaController::class, 'generationReset'])->name('media-generation-reset');
Route::get('/justin/{id}', [App\Http\Controllers\PublicPageController::class, 'JustIn'])->name('justin');

Route::group(['middleware' => ['auth']], function () {
	Route::get('/citizen/journalism/add', [App\Http\Controllers\CitizenController::class, 'add'])->name('add-citizen-journalism');
	Route::post('/citizen/journalism/save', [App\Http\Controllers\CitizenController::class, 'save'])->name('save-citizen-journalism');
	Route::get('/citizen/journalism/lists', [App\Http\Controllers\CitizenController::class, 'publicList'])->name('list-citizen-journalism');
	Route::get('/citizen/journalism/edit/{post}', [App\Http\Controllers\CitizenController::class, 'edit'])->name('edit-citizen-journalism');
	Route::get('/citizen/journalism/delete/{post}', [App\Http\Controllers\CitizenController::class, 'delete'])->name('delete-citizen-journalism');
});

Route::group(["prefix" => "admin", 'middleware' => ['auth', '2fa', 'admin']], function ()
//Route::group(["prefix"=> "admin", 'middleware'=>['auth']], function()
{
	Route::post('/2fa', function () {
		return redirect(route('dashboard'));
	})->name('2fa');
	Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
	Route::post('/user-profile/', [App\Http\Controllers\UserController::class, 'saveUserProfile'])->name('user-profile');
	Route::get('/user-gend-gauth/{id}', [App\Http\Controllers\UserController::class, 'saveUserGoogleAuthentication'])->name('user-send-gauth');

	Route::get('/user-profile-edit/{id}', [App\Http\Controllers\UserController::class, 'userProfile'])->name('user-profile-edit');

	Route::get('/user-list', [App\Http\Controllers\UserController::class, 'getUserList'])->name('user-list');
	Route::get('/subscriber-list', [App\Http\Controllers\UserController::class, 'getSubscriberList'])->name('subscriber-list');
	Route::get('/user/add', [App\Http\Controllers\UserController::class, 'addUser'])->name('user-add');
	Route::get('/user/edit/{id}', [App\Http\Controllers\UserController::class, 'editUser'])->name('user-edit');
	Route::get('/user/delete/{id}', [App\Http\Controllers\UserController::class, 'deleteUser'])->name('user-delete');
	Route::get('/user/delete/permanant/{id}', [App\Http\Controllers\UserController::class, 'deleteUserForce'])->name('user-delete-permanant');
	Route::get('/user/verification/{id}', [App\Http\Controllers\UserController::class, 'verificationMail'])->name('user-verification');
	Route::get('/delete/account/{id}', [App\Http\Controllers\UserController::class, 'deleteAccount'])->name('delete-account');
	Route::post('/user/save', [App\Http\Controllers\UserController::class, 'saveUser'])->name('save-user');
	Route::get('/user-settings/{id}', [App\Http\Controllers\UserController::class, 'getUserSettings'])->name('user-settings');
	Route::post('/save-user-settings', [App\Http\Controllers\UserController::class, 'setUserSettings'])->name('save-user-settings');
	Route::get('/change-password/{id}', [App\Http\Controllers\UserController::class, 'changeUserPassword'])->name('change-password');
	Route::post('/update-password', [App\Http\Controllers\UserController::class, 'updateUserPassword'])->name('update-password');

	Route::get('/settings/site', [App\Http\Controllers\SiteSettingsController::class, 'get'])->name('settings-site');
	Route::get('/settings/social', [App\Http\Controllers\SiteSettingsController::class, 'get'])->name('settings-social');
	Route::post('/settings/site/save', [App\Http\Controllers\SiteSettingsController::class, 'save'])->name('save-site-settings');
	Route::get('/settings/roles', [App\Http\Controllers\SettingsController::class, 'getRoles'])->name('settings-roles');
	Route::get('/settings/roles/add', [App\Http\Controllers\SettingsController::class, 'getRoleForm'])->name('settings-role-add');
	Route::post('/settings/roles/update', [App\Http\Controllers\SettingsController::class, 'setRole'])->name('settings-roles-update');
	Route::get('/settings/roles/{id}', [App\Http\Controllers\SettingsController::class, 'getRole'])->name('settings-role');
	Route::post('/settings/roles/edit', [App\Http\Controllers\SettingsController::class, 'updateRole'])->name('settings-role-edit');
	Route::post('/settings/roles/delete', [App\Http\Controllers\SettingsController::class, 'deleteRole'])->name('settings-role-delete');
	Route::get('/settings/roles/set', [App\Http\Controllers\SettingsController::class, 'setRole'])->name('settings-role-set');
	Route::get('/settings/team', [App\Http\Controllers\SettingsController::class, 'setRole'])->name('settings-team');


	Route::get('/tag/list', [App\Http\Controllers\TagController::class, 'list'])->name('tags');
	Route::get('/tag/list/json', [App\Http\Controllers\TagController::class, 'listJson'])->name('tagsjson');
	Route::get('/tag/add', [App\Http\Controllers\TagController::class, 'add'])->name('add-tag');
	Route::get('/tag/edit/{tag}', [App\Http\Controllers\TagController::class, 'edit'])->name('tag-edit');
	Route::post('/tag/save', [App\Http\Controllers\TagController::class, 'save'])->name('save-tag');
	Route::get('/tag/delete/{tag}', [App\Http\Controllers\TagController::class, 'delete'])->name('tag-delete');

	Route::get('/category/list', [App\Http\Controllers\CategoryController::class, 'list'])->name('categories');
	Route::get('/category/list/json', [App\Http\Controllers\CategoryController::class, 'listJson'])->name('categoriesjson');
	Route::get('/category/add', [App\Http\Controllers\CategoryController::class, 'add'])->name('add-category');
	Route::get('/category/edit/{cat}', [App\Http\Controllers\CategoryController::class, 'edit'])->name('category-edit');
	Route::post('/category/save', [App\Http\Controllers\CategoryController::class, 'save'])->name('save-category');
	Route::get('/category/delete/{cat}', [App\Http\Controllers\CategoryController::class, 'delete'])->name('category-delete');

	Route::get('/page/list', [App\Http\Controllers\PageController::class, 'list'])->name('pages');
	Route::get('/page/list/json', [App\Http\Controllers\PageController::class, 'listJson'])->name('pagejson');
	Route::get('/page/add', [App\Http\Controllers\PageController::class, 'add'])->name('add-page');
	Route::get('/page/edit/{post}', [App\Http\Controllers\PageController::class, 'edit'])->name('page-edit');
	Route::post('/page/save', [App\Http\Controllers\PageController::class, 'save'])->name('save-page');
	Route::get('/page/delete/{post}', [App\Http\Controllers\PageController::class, 'delete'])->name('page-delete');

	Route::get('/post/list', [App\Http\Controllers\PostController::class, 'list'])->name('posts');
	Route::get('/post/list/json', [App\Http\Controllers\PostController::class, 'listJson'])->name('postsjson');
	Route::get('/post/add', [App\Http\Controllers\PostController::class, 'add'])->name('add-post');
	Route::get('/quick/post/add', [App\Http\Controllers\PostController::class, 'addQuick'])->name('quick-post');
	Route::get('/post/edit/{post}', [App\Http\Controllers\PostController::class, 'edit'])->name('post-edit');
	Route::get('/post/seo/{post}', [App\Http\Controllers\PostController::class, 'editSeo'])->name('post-seo');
	Route::post('/post/seo/save', [App\Http\Controllers\PostController::class, 'updatePostSEO'])->name('seo-update-post');
	Route::post('/post/audio/generate', [App\Http\Controllers\PostController::class, 'updatePostAudio'])->name('audio-update-post');
	Route::post('/post/save', [App\Http\Controllers\PostController::class, 'save'])->name('save-post');
	Route::post('/post/save/quick', [App\Http\Controllers\PostController::class, 'quickSave'])->name('save-quick-post');
	Route::get('/post/delete/{post}', [App\Http\Controllers\PostController::class, 'delete'])->name('post-delete');
	Route::get('/post/delete/permanant/{post}', [App\Http\Controllers\PostController::class, 'deletePermanant'])->name('post-delete-permanant');
	Route::get('/citizen/journalism/all/', [App\Http\Controllers\CitizenController::class, 'list'])->name('lists-citizen-journalism');
	Route::get('/citizen/journalism/view/{post}', [App\Http\Controllers\CitizenController::class, 'view'])->name('citizen-journalism-view');
	Route::get('/citizen/journalism/post/{post}', [App\Http\Controllers\CitizenController::class, 'makePost'])->name('citizen-journalism-post');
	Route::get('/citizen/journalism/ignore/{post}', [App\Http\Controllers\CitizenController::class, 'ignore'])->name('citizen-journalism-ignore');

	Route::get('/justin/list', [App\Http\Controllers\JustInController::class, 'list'])->name('justins');
	Route::get('/justin/list/json', [App\Http\Controllers\JustInController::class, 'listJson'])->name('justinsjson');
	Route::get('/justin/add', [App\Http\Controllers\JustInController::class, 'add'])->name('add-justin');
	Route::get('/justin/edit/{post}', [App\Http\Controllers\JustInController::class, 'edit'])->name('justin-edit');
	Route::post('/justin/save', [App\Http\Controllers\JustInController::class, 'save'])->name('save-justin');
	Route::get('/justin/delete/{post}', [App\Http\Controllers\JustInController::class, 'delete'])->name('justin-delete');
	Route::get('/justin/delete/permanant/{post}', [App\Http\Controllers\JustInController::class, 'deletePermanant'])->name('justin-delete-permanant');

	Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'getAll'])->name('notifications');
	Route::get('/notifications/top', [App\Http\Controllers\NotificationController::class, 'getTopFive'])->name('notifications-json');
	Route::get('/notifications/update/{notification}', [App\Http\Controllers\NotificationController::class, 'update'])->name('notifications-update');

	Route::get('/menu/list', [App\Http\Controllers\MenuController::class, 'getAll'])->name('menus');
	Route::get('/menu/add', [App\Http\Controllers\MenuController::class, 'add'])->name('add-menu');
	Route::get('/menu/edit/{menu}', [App\Http\Controllers\MenuController::class, 'get'])->name('menu-edit');
	Route::post('/menu/save', [App\Http\Controllers\MenuController::class, 'save'])->name('save-menu');
	Route::get('/menu/delete/{menu}', [App\Http\Controllers\MenuController::class, 'delete'])->name('menu-delete');
});
// Route::get('/{x}', [App\Http\Controllers\PublicPageController::class, 'handleRoute'])
// 	->where('x', '.*');

// Route::get('/{x}', [App\Http\Controllers\PublicPageController::class, 'handleRoute'])
//     ->where('x', '^(?!v1).*');
Route::get('/search', [App\Http\Controllers\PublicPageController_v1::class, 'search'])->name('v1.search');
Route::get('/{x}', [App\Http\Controllers\PublicPageController_v1::class, 'handleRoute'])
    ->where('x', '^(?!admin|v1|api).*')
    ->name('public.page');