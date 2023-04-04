<?php

use App\Services\VirusTotalService;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::group(['middleware' => 'guest'], function () {
    Route::get('login',[UserController::class,'showLoginForm'])->name('login');
    Route::post('login', [UserController::class,'performLogin']);
    Route::get('registration',[UserController::class,'showRegistrationForm'])->name('registration');
    Route::post('registration', [UserController::class,'performRegistration']);
    Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm']);
    Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm']);
    Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm']);
    Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm']);
    Route::get('reset-success', [ForgotPasswordController::class, 'showResetPasswordSuccess']);
});

Route::group(['middleware' => 'auth'], function() {
    Route::get('/', [UserController::class,'showHomePage']);
    Route::get('/logout',[UserController::class,'logout']);
    Route::get('/message/{id}',[UserController::class,'getMessage']);
    Route::post('message', [UserController::class,'sendMessage']);
    Route::get('/check', function () {
        $virusTotalService = new VirusTotalService();
        $url = 'https://example.com';
        // $url = 'https://testsafebrowsing.appspot.com/s/phishing.html';
        $isSafe = $virusTotalService->isSafeUrl($url);
        if ($isSafe) {
            return response()->json(['isSafe' => true]);
        } else {
            return response()->json(['isSafe' => false]);
        }
    });

});
