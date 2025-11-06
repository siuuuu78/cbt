<div class="max-w-6xl mx-auto py-8 px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Kursus Saya</h1>
        <a href="{{ route('courses.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Buat Kursus
        </a>
    </div>

    @if($courses->isEmpty())
        <div class="text-center py-12 text-gray-500">
            Belum ada kursus. <a href="{{ route('courses.create') }}" class="text-blue-600">Buat sekarang</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($courses as $course)
                <div class="border rounded-lg overflow-hidden shadow">
                    @if($course->thumbnail)
                        <img src="{{ asset('storage/' . $course->thumbnail) }}"
                             alt="{{ $course->title }}"
                             class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-500">No Image</span>
                        </div>
                    @endif
                    <div class="p-4">
                        <h3 class="font-bold text-lg">{{ $course->title }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $course->lessons_count }} lesson</p>
                        <p class="text-sm mt-2">
                            @if($course->price > 0)
                                Rp {{ number_format($course->price, 0, ',', '.') }}
                            @else
                                Gratis
                            @endif
                        </p>
                        <div class="mt-4 flex space-x-2">
                            <a href="{{ route('lessons.index', $course) }}" class="text-blue-600 text-sm">Kelola Lesson</a>
                            <a href="{{ route('courses.edit', $course) }}" class="text-gray-600 text-sm">Edit</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
