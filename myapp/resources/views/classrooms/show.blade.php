<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $classroom->course_name }}
                    <span class="text-gray-400 font-normal text-base ml-2">{{ $classroom->course_code }}</span>
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">Year {{ $classroom->year }} &mdash; Block
                    {{ $classroom->block }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('students.create', $classroom) }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg">
                    + Add Student
                </a>
                <a href="{{ route('classrooms.slideshow', $classroom) }}"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg">
                    ▶ Slideshow
                </a>
                <a href="{{ route('classrooms.export.template', $classroom) }}"
                    class="bg-green-600 hover:bg-green-700 text-white text-sm px-4 py-2 rounded-lg">
                    ⬇ Template
                </a>
                <a href="{{ route('classrooms.export.students', $classroom) }}"
                    class="bg-teal-600 hover:bg-teal-700 text-white text-sm px-4 py-2 rounded-lg">
                    📊 Export Excel
                </a>
                <a href="{{ route('classrooms.export.pdf', $classroom) }}"
                    class="bg-red-600 hover:bg-red-700 text-white text-sm px-4 py-2 rounded-lg">
                    📄 Export PDF
                </a>
                <form action="{{ route('classrooms.import', $classroom) }}" method="POST" enctype="multipart/form-data"
                    class="inline-flex items-center gap-2">
                    @csrf
                    <label
                        class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg cursor-pointer">
                        📥 Import Excel
                        <input type="file" name="file" accept=".xlsx,.xls" class="hidden" onchange="this.form.submit()">
                    </label>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if($students->isEmpty())
                <div class="text-center py-16 text-gray-500">
                    <p class="text-lg">No students yet.</p>
                    <a href="{{ route('students.create', $classroom) }}"
                        class="text-blue-600 underline mt-2 inline-block">Add your first student</a>
                </div>
            @else
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Student ID
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Course / Year
                                    / Block</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Lab Grade
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Lec Grade
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Average</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($students as $student)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $student->student_id }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $student->full_name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $student->course }} / {{ $student->year }} /
                                        {{ $student->block }}</td>
                                    <td class="px-4 py-3 text-sm text-center text-gray-700">
                                        {{ $student->laboratory_grade ?? '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-center text-gray-700">
                                        {{ $student->lecture_grade ?? '—' }}
                                    </td>
                                    <td
                                        class="px-4 py-3 text-sm text-center font-semibold
                                                {{ $student->average_grade >= 75 ? 'text-green-600' : ($student->average_grade !== null ? 'text-red-500' : 'text-gray-400') }}">
                                        {{ $student->average_grade !== null ? number_format($student->average_grade, 2) : '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('students.edit', [$classroom, $student]) }}"
                                                class="text-xs bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-lg">
                                                Edit
                                            </a>
                                            <form action="{{ route('students.destroy', [$classroom, $student]) }}" method="POST"
                                                onsubmit="return confirm('Remove this student?')">
                                                @csrf @method('DELETE')
                                                <button
                                                    class="text-xs bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg">
                                                    Remove
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>