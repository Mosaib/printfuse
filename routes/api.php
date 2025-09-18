<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CompanyController;

Route::get('/', function () {
    $version = "PrintFuse api v 1.0";
    return $version;
});

Route::post('/userRegister', [RegisteredUserController::class, 'registerUser']);
Route::post('/login', [RegisteredUserController::class, 'login']);
Route::post('/logout', [RegisteredUserController::class, 'logout'])->middleware('auth:sanctum');

//company
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/companies', [CompanyController::class, 'index']);
    Route::post('/companies', [CompanyController::class, 'store']);
    Route::put('/companyUpdate', [CompanyController::class, 'update']);
    Route::delete('/companyDelete', [CompanyController::class, 'destroy']);
    Route::post('/company/switch', [CompanyController::class, 'switchActiveCompany']);
});