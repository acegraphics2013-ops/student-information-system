<?php

use App\Http\Controllers\AcademicProgramController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Models\College;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\Program;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard', [
        'studentCount' => Student::count(),
        'subjectCount' => Subject::count(),
        'enrollmentCount' => Enrollment::count(),
        'collegeCount' => College::count(),
        'departmentCount' => Department::count(),
        'programCount' => Program::count(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function (): void {
            Route::get('/', AdminDashboardController::class)->name('index');
            Route::resource('users', AdminUserController::class)
                ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
        });

    Route::get('/academic-programs', [AcademicProgramController::class, 'index'])
        ->name('academic-programs.index');

    Route::resource('students', StudentController::class)
        ->middlewareFor(
            ['destroy'],
            'role:admin'
        );

    Route::resource('subjects', SubjectController::class)
        ->middlewareFor(
            ['create', 'store', 'edit', 'update', 'destroy'],
            'role:admin'
        );

    Route::resource('enrollments', EnrollmentController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])->name('profile.photo.destroy');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
