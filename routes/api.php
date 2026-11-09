<?php

use App\Http\Controllers\Api\StudentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function (): void {
    Route::apiResource('students', StudentController::class)
        ->middlewareFor(
            ['store', 'update', 'destroy'],
            'role:admin'
        );
});
