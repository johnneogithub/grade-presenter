<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function create(Classroom $classroom)
    {
        return view('students.create', compact('classroom'));
    }

    public function store(Request $request, Classroom $classroom)
    {
        $request->validate([
            'student_id'      => 'required|string|max:50',
            'last_name'       => 'required|string|max:100',
            'first_name'      => 'required|string|max:100',
            'middle_initial'  => 'nullable|string|max:5',
            'course'          => 'required|string|max:100',
            'year'            => 'required|string|max:20',
            'block'           => 'required|string|max:20',
            'laboratory_grade'=> 'nullable|numeric|min:0|max:100',
            'lecture_grade'   => 'nullable|numeric|min:0|max:100',
        ]);

        $classroom->students()->create($request->all());

        return redirect()->route('classrooms.show', $classroom)
                         ->with('success', 'Student added successfully.');
    }

    public function edit(Classroom $classroom, Student $student)
    {
        return view('students.edit', compact('classroom', 'student'));
    }

    public function update(Request $request, Classroom $classroom, Student $student)
    {
        $request->validate([
            'student_id'      => 'required|string|max:50',
            'last_name'       => 'required|string|max:100',
            'first_name'      => 'required|string|max:100',
            'middle_initial'  => 'nullable|string|max:5',
            'course'          => 'required|string|max:100',
            'year'            => 'required|string|max:20',
            'block'           => 'required|string|max:20',
            'laboratory_grade'=> 'nullable|numeric|min:0|max:100',
            'lecture_grade'   => 'nullable|numeric|min:0|max:100',
        ]);

        $student->update($request->all());

        return redirect()->route('classrooms.show', $classroom)
                         ->with('success', 'Student updated successfully.');
    }

    public function destroy(Classroom $classroom, Student $student)
    {
        $student->delete();
        return redirect()->route('classrooms.show', $classroom)
                         ->with('success', 'Student removed successfully.');
    }
}