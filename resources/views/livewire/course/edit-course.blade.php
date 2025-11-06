<div class="max-w-3xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-6">Edit Kursus: {{ $course->title }}</h1>

    <form wire:submit.prevent="update" class="space-y-6">
        <div>
            <label class="block font-medium mb-2">Judul Kursus</label>
            <input type="text" wire:model="title" class="w-full border rounded p-2">
            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-medium mb-2">Deskripsi</label>
            <textarea wire:model="description" rows="4" class="w-full border rounded p-2"></textarea>
            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-medium mb-2">Harga (Rp)</label>
            <input type="number" min="0" wire:model="price" class="w-full border rounded p-2">
            @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-medium mb-2">Thumbnail</label>
            @if($currentThumbnail)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $currentThumbnail) }}"
                         alt="Thumbnail saat ini"
                         class="w-32 h-32 object-cover rounded">
                </div>
            @endif

            <input type="file" wire:model="thumbnail" accept="image/*" class="block">
            @if($thumbnail)
                <div class="mt-2">
                    <img src="{{ $thumbnail->temporaryUrl() }}" class="w-32 h-32 object-cover rounded">
                </div>
            @endif
            @error('thumbnail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            <p class="text-sm text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengganti.</p>
        </div>

        <div class="pt-4">
            <button type="submit"
                    wire:loading.attr="disabled"
                    class="bg-green-600 text-white px-6 py-2 rounded font-medium disabled:opacity-50">
                <span wire:loading.remove>Simpan Perubahan</span>
                <span wire:loading>Sedang menyimpan...</span>
            </button>
            <a href="{{ route('courses.index') }}" class="ml-4 text-gray-600">Batal</a>
        </div>
    </form>
</div>
