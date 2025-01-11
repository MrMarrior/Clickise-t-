<?php

use App\Http\Controllers\ConfirmationActionController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SettingsController::class, 'showSettings']);
Route::get('/getUserData', [SettingsController::class, 'getUserData']);
Route::post('/settings/sendCode', [ConfirmationActionController::class, 'sendCode']);
Route::post('/settings/changeSettings', [SettingsController::class, 'changeSettings']);
