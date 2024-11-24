<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/users-filter', [UserController::class, 'filter']);
Route::apiResource('users', UserController::class);

Route::get('/country-filter', [CountryController::class, 'filter']);
Route::apiResource('countries', CountryController::class);


Route::apiResource('companies', CompanyController::class);
Route::post('/submissions', [SubmissionController::class, 'store']);
