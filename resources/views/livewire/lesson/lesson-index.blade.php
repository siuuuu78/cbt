<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Kelola Lesson: {{ $course->title }}</h1>
        <a href="{{ route('courses.index') }}" class="text-blue-600">← Kembali ke Daftar Kursus</a>
    </div>

    @if(session()->has('message'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-6">
            {{ session('message') }}
        </div>
    @endif

    <!-- Form Tambah Lesson -->
    <div class="bg-white p-6 rounded shadow mb-8">
        <h2 class="text-xl font-semibold mb-4">Tambah Lesson Baru</h2>
        <div class="space-y-4">
            <div>
                <label class="block font-medium mb-2">Judul Lesson</label>
                <input type="text" wire:model="title" class="w-full border rounded p-2">
            </div>
            <div>
                <label class="block font-medium mb-2">URL Video (YouTube/Vimeo)</label>
                <input type="url" wire:model="video_url" class="w-full border rounded p-2" placeholder="https://youtube.com/...">
            </div>
            <div>
                <label class="block font-medium mb-2">Konten (Opsional)</label>
                <textarea wire:model="content" rows="6" class="w-full border rounded p-2"
                          placeholder="Kamu bisa tulis teks atau paste HTML dengan gambar..."></textarea>
                <p class="text-sm text-gray-500 mt-1">
                    💡 Untuk gambar: upload di tempat lain lalu paste URL-nya, atau gunakan editor HTML nanti.
                </p>
            </div>
            <div>
                <label class="block font-medium mb-2">Urutan (opsional)</label>
                <input type="number" wire:model="order" class="w-full border rounded p-2" placeholder="1, 2, 3...">
            </div>
            <button wire:click="addLesson" class="bg-blue-600 text-white px-4 py-2 rounded">
                Tambah Lesson
            </button>
        </div>
    </div>

    <!-- Daftar Lesson -->
    <div>
        <h2 class="text-xl font-semibold mb-4">Daftar Lesson ({{ $lessons->count() }})</h2>
        @if($lessons->isEmpty())
            <p class="text-gray-500">Belum ada lesson.</p>
        @else
            <div class="space-y-3">
                @foreach($lessons as $lesson)
                    <div class="flex items-center justify-between p-4 border rounded">
                        <div>
                            <strong>{{ $lesson->order }}. {{ $lesson->title }}</strong>
                            @if($lesson->is_locked)
                                <span class="ml-2 text-red-500">🔒 Dikunci</span>
                            @endif
                        </div>
                        <div>
                            <a href="{{ route('lessons.quiz', $lesson) }}" class="text-blue-600 text-sm mr-3">
                                {{ $lesson->quiz ? 'Edit Kuis' : 'Tambah Kuis' }}
                            </a>
                            <a href="#" class="text-gray-600 text-sm">Edit</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
