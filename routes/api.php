<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\EcoleController;
use App\Http\Controllers\PasswordResetRequestController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::apiResource('roles', RoleController::class);
Route::apiResource('ecoles', EcoleController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('students', StudentController::class);



Route::post('users/login', [AuthController::class, 'login']);
Route::post('users/logout', [AuthController::class, 'logout']);


Route::post('reset_password_request', [PasswordResetRequestController::class, 'sendPasswordResetEmail']);
Route::post('change_password', [ChangePasswordController::class, 'passwordReset']);
