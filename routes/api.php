<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\EcoleController;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\FiliereController;
use App\Http\Controllers\NiveauController;
use App\Http\Controllers\PasswordResetRequestController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Models\Etudiant;
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
Route::get('niveaux/ecole/{id}', [NiveauController::class, 'niveauByEcole']);
//Route::get('etudiants/ecole/{id}', [EtudiantController::class, 'elevesByEcole']);


Route::post('users/login', [AuthController::class, 'login']);
Route::post('reset_password_request', [PasswordResetRequestController::class, 'sendPasswordResetEmail']);
Route::post('change_password', [ChangePasswordController::class, 'passwordReset']);

// Route::group(['middleware' => 'auth:sanctum'],function(){
//     Route::apiResource('ecoles', EcoleController::class);
//     Route::apiResource('etudiants', EtudiantController::class);
// });

//   Route::get('etudiants/ecole/{id}', [EtudiantController::class, 'elevesByEcole']);
Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::post('users/modifier', [UserController::class, 'modifier']);
    Route::post('ecoles/modifier', [UserController::class, 'modifierEcole']);
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('ecoles', EcoleController::class);
    Route::apiResource('users', UserController::class);
    Route::get('users/usersByEcole/{ecole_id}', [UserController::class, 'getUsersByEcole']);
    Route::post('users/modifierUser', [UserController::class, 'modifierUser']);
    Route::post('ecoles/modifierEcole', [EcoleController::class, 'modifierEcole']);
    Route::post('ecoles/checkEmail', [EcoleController::class, 'checkEmail']);
    Route::post('ecoles/checkEmailUpdate', [EcoleController::class, 'checkEmailUpdate']);
    Route::post('user/checkEmail', [UserController::class, 'checkEmail']);
    Route::delete('users/supprimer/{id}', [UserController::class, 'supprimer']);
    Route::delete('ecoles/supprimer/{id}', [EcoleController::class, 'supprimer']);
    Route::apiResource('niveaux', NiveauController::class);
    Route::apiResource('filieres', FiliereController::class);











    Route::post('ecole/isExist', [EtudiantController::class, 'IsExistGtin']);
    Route::get('filieres/ecole/{id}', [FiliereController::class, 'filiereByEcole']);
    Route::get('suggestions', [EtudiantController::class, 'detDepartment']);
    Route::get('suggestionFilieres', [EtudiantController::class, 'detFiliere']);
    Route::get('suggestionNiveaux', [EtudiantController::class, 'detNiveau']);
    Route::get('suggestionTypeEcoles', [EcoleController::class, 'existTypeEcole']);


    Route::apiResource('etudiants', EtudiantController::class);
    Route::get('etudiants/ecole/{id}', [EtudiantController::class, 'elevesByEcole']);
    Route::post('etudiants/ecole/etudiantsByGTIN', [EtudiantController::class, 'elevesEcoleByGtin']);
    Route::post('etudiants/update', [EtudiantController::class, 'modifier']);
    Route::post('etudiants/valider', [EtudiantController::class, 'valider']);
    Route::post('users/logout', [AuthController::class, 'logout']);
    Route::delete('etudiants/supprimer/{id}', [EtudiantController::class, 'supprimer']);
});
