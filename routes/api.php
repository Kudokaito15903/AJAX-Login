<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//route để viết api đặt bên này
Route::post('/register', [UserController::class,'saveUser']);
Route::put('/profile-update',[UserController::class,'profileUpdate']);
Route::delete('/deleteUser',[UserController::class,'deleteAccountAPI']);
Route::put('/resetPassword',[UserController::class,'resetPassword']);
Route::get("/users", [UserController::class, 'AllUser']);
