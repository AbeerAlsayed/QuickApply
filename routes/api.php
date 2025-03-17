<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/users-filter', [UserController::class, 'filter']);
Route::apiResource('users', UserController::class);

Route::get('/country-filter', [CountryController::class, 'filter']);
Route::apiResource('countries', CountryController::class);
Route::apiResource('positions', PositionController::class);
Route::get('/positions-from-enum', [PositionController::class, 'getPositionsFromEnum']);


Route::apiResource('companies', CompanyController::class);
Route::post('/submissions/notify-companies', [SubmissionController::class, 'notifyCompanies']);
Route::get('/export-company', [CompanyController::class, 'exportCompanies']);

Route::get('/submissions/{country}', [SubmissionController::class, 'getSubmissionsByCountry']);
Route::get('/user/{userId}/companies', [UserController::class, 'getUserCompaniesWithPositions']);
Route::get('/companies-by-country/{countryId}/user/{userId}', [CompanyController::class, 'getCompaniesByCountry']);


Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
});
