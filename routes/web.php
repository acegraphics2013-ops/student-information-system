<?php

use App\Http\Controllers\ProfileController;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard', [
        'studentCount' => Student::count(),
        'subjectCount' => Subject::count(),
        'enrollmentCount' => Enrollment::count(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('students', StudentController::class)
        ->middleware('auth')
        ->middlewareFor(
            ['create', 'store', 'edit', 'update', 'destroy'],
            'role:admin'
        );

    Route::view('/admin', 'admin.index')
        ->middleware('role:admin')
        ->name('admin.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
