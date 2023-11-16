<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
Route::get('/login', [UserController::class, 'index'])->name("login");
Route::get('/register', [UserController::class, 'register'])->name("register");
Route::get ('/forgot',[UserController::class,'ForgotPassword']);
Route::put('/resetPassword',[UserController::class,'resetPassword'])->name('auth.reset');
Route::post('/forgot', [UserController::class, 'SendMailPassword'])->name('auth.forgot');


Route::delete('/deleteUser',[UserController::class,'deleteAccount'])->name('auth.delete');

Route::post('/register', [UserController::class,'saveUser'])->name('auth.register');
Route::post('/login', [UserController::class, 'loginUser'])->name("auth.login");

Route::get('/reset/{email}/{token}',[UserController::class, 'reset'])->name('reset');

Route::group( ['middleware'=>['LoginCheck']],function(){
    Route::get('/', [UserController::class, 'index']);
    Route::get('/profile', [UserController::class,'profile'])->name('profile');
    Route::get ('/logout',[UserController::class, 'logout'])->name('auth.logout');
    Route::post('/profile-image',[UserController::class, 'profileImageUpdate'])->name('profile.image');
    Route::put('/profile-update',[UserController::class,'profileUpdate'])->name('profile.update');
});
Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});
