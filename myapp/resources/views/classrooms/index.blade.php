<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Classrooms') }}
            </h2>
            <a href="{{ route('classrooms.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg">
                + New Classroom
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if($classrooms->isEmpty())
                <div class="text-center py-16 text-gray-500">
                    <p class="text-lg">No classrooms yet.</p>
                    <a href="{{ route('classrooms.create') }}" class="text-blue-600 underline mt-2 inline-block">Create your first classroom</a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($classrooms as $classroom)
                        <div class="bg-white rounded-xl shadow p-6 flex flex-col justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">{{ $classroom->course_name }}</h3>
                                <p class="text-sm text-gray-500">{{ $classroom->course_code }} &mdash; Year {{ $classroom->year }}, Block {{ $classroom->block }}</p>
                                <p class="mt-2 text-sm text-gray-600">{{ $classroom->students_count }} student(s)</p>
                            </div>
                            <div class="mt-4 flex flex-wrap gap-2">
                                <a href="{{ route('classrooms.show', $classroom) }}"
                                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs px-3 py-1.5 rounded-lg">
                                    View
                                </a>
                                <a href="{{ route('classrooms.slideshow', $classroom) }}"
                                   class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs px-3 py-1.5 rounded-lg">
                                    ▶ Slideshow
                                </a>
                                <a href="{{ route('classrooms.edit', $classroom) }}"
                                   class="bg-yellow-400 hover:bg-yellow-500 text-white text-xs px-3 py-1.5 rounded-lg">
                                    Edit
                                </a>
                                <form action="{{ route('classrooms.destroy', $classroom) }}" method="POST"
                                      onsubmit="return confirm('Delete this classroom?')">
                                    @csrf @method('DELETE')
                                    <button class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1.5 rounded-lg">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>