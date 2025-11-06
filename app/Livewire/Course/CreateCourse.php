<?php

namespace App\Http\Livewire\Course;

use Livewire\Component;
use App\Models\Course;
use Livewire\WithFileUploads;

class CreateCourse extends Component
{
    use WithFileUploads;

    public $title = "";
    public $description = "";
    public $price = 0;
    public $thumbnail;

    protected $rules = [
        "title" => "required|string|max:255",
        "description" => "required|string",
        "price" => "required|numeric|min:0",
        "thumbnail" => "nullable|image|max:2048", // max 2MB
    ];

    public function mount()
    {
        if (!auth()->user()->hasRole("instructor")) {
            abort(403, "Hanya instruktur yang bisa mengakses halaman ini.");
        }
    }

    public function save()
    {
        $this->validate();

        $thumbnailPath = null;
        if ($this->thumbnail) {
            $thumbnailPath = $this->thumbnail->store(
                "course-thumbnails",
                "public",
            );
        }

        $course = auth()
            ->user()
            ->courses()
            ->create([
                "title" => $this->title,
                "description" => $this->description,
                "price" => $this->price,
                "thumbnail" => $thumbnailPath,
                "is_published" => false,
            ]);

        session()->flash("message", "Kursus berhasil dibuat!");
        return redirect()->route("lessons.index", $course);
    }

    public function render()
    {
        return view("livewire.course.create-course");
    }
}
