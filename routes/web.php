<?php

use App\Http\Controllers\AppleController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AuthOtpController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LoginWithGoogleController;
use App\Http\Controllers\FacebookController;
use App\Http\Controllers\GithubController;
use App\Http\Controllers\NotificationSendController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function () {
    // Route::get('otp/register', [RegisterController::class, 'register'])->name('otp.register');
    // Route::post('otp/generateRegister', [RegisterController::class, 'generateRegister'])->name('otp.generateRegister');
    // Route::get('otp/verificationRegister/{user_id}', [RegisterController::class, 'verificationRegister'])->name('otp.verificationRegister');
    // Route::post('otp/register', [AuthOtpController::class, 'registerWithOtp'])->name('otp.getregister');
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);
    Route::post('/store-token', [NotificationSendController::class, 'updateDeviceToken'])->name('store.token');
    Route::post('/send-web-notification', [NotificationSendController::class, 'sendNotification'])->name('send.web-notification');
});


Route::get('otp/register', [RegisterController::class, 'register'])->name('otp.register');
Route::post('otp/generateRegister', [RegisterController::class, 'generateRegister'])->name('otp.generateRegister');
Route::get('otp/verificationRegister', [RegisterController::class, 'verificationRegister'])->name('otp.verificationRegister');
Route::post('otp/register', [AuthOtpController::class, 'registerWithOtp'])->name('otp.getregister');

Route::get('otp/login', [AuthOtpController::class, 'login'])->name('otp.login');
Route::post('otp/generate', [AuthOtpController::class, 'generate'])->name('otp.generate');
Route::get('otp/verification/{user_id}', [AuthOtpController::class, 'verification'])->name('otp.verification');
Route::post('otp/login', [AuthOtpController::class, 'loginWithOtp'])->name('otp.getlogin');


Route::get('userscrete', [UserController::class, 'createPDF'])->name('userscrete');
Route::get('usersviewPDF', [UserController::class, 'viewPDF']);



Route::get('users-export', [UserController::class, 'export'])->name('users.export');
Route::post('users-import', [UserController::class, 'import'])->name('users.import');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('authorized/google', [LoginWithGoogleController::class, 'redirectToGoogle']);
Route::get('authorized/google/callback', [LoginWithGoogleController::class, 'handleGoogleCallback']);

Route::get('/redirect', [FacebookController::class, 'redirectFacebook']);
Route::get('/callback', [FacebookController::class, 'facebookCallback']);

Route::get('auth/github', [GithubController::class, 'redirectToGithub']);
Route::get('auth/github/callback', [GithubController::class, 'handleGithubCallback']);

Route::get('auth/apple', [AppleController::class, 'redirectToApple']);
Route::get('auth/apple/callback', [AppleController::class, 'handleAppleCallback']);

Route::get('contact-us', [ContactController::class, 'index']);
Route::post('contact-us', [ContactController::class, 'store'])->name('contact.us.store');
