<?php

namespace App\Http\Livewire\Course;

use Livewire\Component;
use App\Models\Course;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class EditCourse extends Component
{
    use WithFileUploads;

    public Course $course;
    public $title = "";
    public $description = "";
    public $price = 0;
    public $thumbnail; // untuk file upload baru
    public $currentThumbnail = ""; // thumbnail lama (untuk tampilan)

    protected $rules = [
        "title" => "required|string|max:255",
        "description" => "required|string",
        "price" => "required|numeric|min:0",
        "thumbnail" => "nullable|image|max:2048", // max 2MB
    ];

    public function mount(Course $course)
    {
        // Hanya pemilik kursus atau admin yang boleh edit
        if (
            auth()->id() !== $course->user_id &&
            !auth()->user()->hasRole("admin")
        ) {
            abort(403, "Anda tidak diizinkan mengedit kursus ini.");
        }

        $this->course = $course;
        $this->title = $course->title;
        $this->description = $course->description;
        $this->price = $course->price;
        $this->currentThumbnail = $course->thumbnail;
    }

    public function update()
    {
        $this->validate();

        // Handle upload thumbnail baru
        $thumbnailPath = $this->currentThumbnail; // default: pakai yang lama

        if ($this->thumbnail) {
            // Hapus thumbnail lama jika ada
            if ($this->currentThumbnail) {
                Storage::disk("public")->delete($this->currentThumbnail);
            }
            // Simpan yang baru
            $thumbnailPath = $this->thumbnail->store(
                "course-thumbnails",
                "public",
            );
        }

        $this->course->update([
            "title" => $this->title,
            "description" => $this->description,
            "price" => $this->price,
            "thumbnail" => $thumbnailPath,
        ]);

        session()->flash("message", "Kursus berhasil diperbarui!");
        return redirect()->route("courses.index");
    }

    public function render()
    {
        return view("livewire.course.edit-course");
    }
}
