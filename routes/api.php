<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\VacationPlanController;

Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware('auth-api')->group( function () {
    Route::apiResource('vacation-plan', VacationPlanController::class);
    Route::get('vacation-plan/{vacationPlan}/generate-pdf', [VacationPlanController::class, 'generatePDF']);
});
