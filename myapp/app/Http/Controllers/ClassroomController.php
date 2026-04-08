<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::withCount('students')->latest()->get();
        return view('classrooms.index', compact('classrooms'));
    }

    public function create()
    {
        return view('classrooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_name' => 'required|string|max:255',
            'course_code' => 'required|string|max:50',
            'year'        => 'required|string|max:50',
            'block'       => 'required|string|max:50',
        ]);

        Classroom::create($request->all());

        return redirect()->route('classrooms.index')
                         ->with('success', 'Classroom created successfully.');
    }

    public function show(Classroom $classroom)
    {
        $students = $classroom->students()->orderBy('last_name')->get();
        return view('classrooms.show', compact('classroom', 'students'));
    }

    public function edit(Classroom $classroom)
    {
        return view('classrooms.edit', compact('classroom'));
    }

    public function update(Request $request, Classroom $classroom)
    {
        $request->validate([
            'course_name' => 'required|string|max:255',
            'course_code' => 'required|string|max:50',
            'year'        => 'required|string|max:50',
            'block'       => 'required|string|max:50',
        ]);

        $classroom->update($request->all());

        return redirect()->route('classrooms.index')
                         ->with('success', 'Classroom updated successfully.');
    }

    public function destroy(Classroom $classroom)
    {
        $classroom->delete();
        return redirect()->route('classrooms.index')
                         ->with('success', 'Classroom deleted successfully.');
    }
}