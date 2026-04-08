<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Student &mdash; {{ $classroom->course_name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow p-8">
                <form action="{{ route('students.store', $classroom) }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Student ID</label>
                        <input type="text" name="student_id" value="{{ old('student_id') }}"
                               class="mt-1 w-full rounded-lg border-gray-300 shadow-sm" required>
                        @error('student_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}"
                                   class="mt-1 w-full rounded-lg border-gray-300 shadow-sm" required>
                            @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}"
                                   class="mt-1 w-full rounded-lg border-gray-300 shadow-sm" required>
                            @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">M.I.</label>
                            <input type="text" name="middle_initial" value="{{ old('middle_initial') }}"
                                   class="mt-1 w-full rounded-lg border-gray-300 shadow-sm" maxlength="5">
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Course</label>
                            <input type="text" name="course" value="{{ old('course') }}"
                                   class="mt-1 w-full rounded-lg border-gray-300 shadow-sm"
                                   placeholder="e.g. BSIT" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Year</label>
                            <input type="text" name="year" value="{{ old('year') }}"
                                   class="mt-1 w-full rounded-lg border-gray-300 shadow-sm"
                                   placeholder="e.g. 3rd" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Block</label>
                            <input type="text" name="block" value="{{ old('block') }}"
                                   class="mt-1 w-full rounded-lg border-gray-300 shadow-sm"
                                   placeholder="e.g. A" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Laboratory Grade</label>
                            <input type="number" name="laboratory_grade" value="{{ old('laboratory_grade') }}"
                                   step="0.01" min="0" max="100"
                                   class="mt-1 w-full rounded-lg border-gray-300 shadow-sm"
                                   placeholder="0 - 100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Lecture Grade</label>
                            <input type="number" name="lecture_grade" value="{{ old('lecture_grade') }}"
                                   step="0.01" min="0" max="100"
                                   class="mt-1 w-full rounded-lg border-gray-300 shadow-sm"
                                   placeholder="0 - 100">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('classrooms.show', $classroom) }}"
                           class="px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">
                            Cancel
                        </a>
                        <button type="submit"
                                class="px-5 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg">
                            Add Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>