<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Classroom') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow p-8">
                <form action="{{ route('classrooms.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Course Name</label>
                        <input type="text" name="course_name" value="{{ old('course_name') }}"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="e.g. Web Development" required>
                        @error('course_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Course Code</label>
                        <input type="text" name="course_code" value="{{ old('course_code') }}"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="e.g. IT401" required>
                        @error('course_code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Year</label>
                            <input type="text" name="year" value="{{ old('year') }}"
                                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="e.g. 3rd" required>
                            @error('year') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Block</label>
                            <input type="text" name="block" value="{{ old('block') }}"
                                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="e.g. A" required>
                            @error('block') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('classrooms.index') }}"
                            class="px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-5 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg">
                            Create Classroom
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>