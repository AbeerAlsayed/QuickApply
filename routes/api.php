<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
});

Route::get('/users-filter', [UserController::class, 'filter']);
Route::apiResource('users', UserController::class);

Route::get('/country-filter', [CountryController::class, 'filter']);
Route::apiResource('countries', CountryController::class);

Route::apiResource('positions', PositionController::class);
Route::get('/positions-from-enum', [PositionController::class, 'getPositionsFromEnum']);


Route::apiResource('companies', CompanyController::class);
Route::get('/export-company', [CompanyController::class, 'exportCompanies']);



Route::post('/submissions/notify-companies', [SubmissionController::class, 'notifyCompanies'])
    ->middleware('auth:sanctum');
Route::middleware('auth:sanctum')
    ->get('/companies-by-country/{countryId}', [SubmissionController::class, 'getCompaniesByCountry']);




