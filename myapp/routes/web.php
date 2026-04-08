<?php

use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('classrooms.index');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Classrooms
    Route::resource('classrooms', ClassroomController::class);

    // Students (nested under classrooms)
    Route::get('classrooms/{classroom}/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('classrooms/{classroom}/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('classrooms/{classroom}/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('classrooms/{classroom}/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('classrooms/{classroom}/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

    // Slideshow
    Route::get('classrooms/{classroom}/slideshow', function (App\Models\Classroom $classroom) {
        $students = $classroom->students()->orderBy('last_name')->get();
        return view('classrooms.slideshow', compact('classroom', 'students'));
    })->name('classrooms.slideshow');

});

require __DIR__.'/auth.php';