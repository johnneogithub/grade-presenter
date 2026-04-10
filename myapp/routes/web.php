<?php

use App\Exports\StudentsExport;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Imports\StudentsImport;
use App\Models\Classroom;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('classrooms.index');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return redirect()->route('classrooms.index');
    })->middleware(['auth', 'verified'])->name('dashboard');

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
    Route::get('classrooms/{classroom}/slideshow', function (Classroom $classroom) {
        $students = $classroom->students()->orderBy('last_name')->get();
        return view('classrooms.slideshow', compact('classroom', 'students'));
    })->name('classrooms.slideshow');

    // Download blank template
    Route::get('classrooms/{classroom}/export/template', function (Classroom $classroom) {
        return (new StudentsExport($classroom))->downloadTemplate();
    })->name('classrooms.export.template');

    // Export existing students to Excel
    Route::get('classrooms/{classroom}/export/students', function (Classroom $classroom) {
        return (new StudentsExport($classroom))->download();
    })->name('classrooms.export.students');

    // Export students to PDF
    Route::get('classrooms/{classroom}/export/pdf', function (Classroom $classroom) {
        $students = $classroom->students()->orderBy('last_name')->get();
        $pdf = Pdf::loadView('classrooms.pdf', compact('classroom', 'students'));
        return $pdf->download($classroom->course_code . '_students.pdf');
    })->name('classrooms.export.pdf');

    // Import students from uploaded Excel file
    Route::post('classrooms/{classroom}/import', function (
        Request $request,
        App\Models\Classroom $classroom
    ) {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls']);

        $file = $request->file('file');
        $realPath = $file->getRealPath();

        $importer = new App\Imports\StudentsImport($classroom);
        $count = $importer->import($realPath);

        return redirect()->route('classrooms.show', $classroom)
            ->with('success', "{$count} student(s) imported successfully!");
    })->name('classrooms.import');
});

require __DIR__ . '/auth.php';
