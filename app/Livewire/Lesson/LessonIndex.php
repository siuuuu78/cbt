<?php

namespace App\Http\Livewire\Lesson;

use Livewire\Component;
use App\Models\Course;
use Livewire\WithFileUploads;

class LessonIndex extends Component
{
    use WithFileUploads;

    public Course $course;
    public $lessons = [];
    public $title = "";
    public $video_url = "";
    public $content = "";
    public $order = 0;

    protected $rules = [
        "title" => "required|string|max:255",
        "video_url" => "required|url",
        "content" => "nullable|string",
        "order" => "nullable|integer",
    ];

    public function mount(Course $course)
    {
        $this->course = $course;
        if (!auth()->user()->hasRole("instructor")) {
            abort(403, "Hanya instruktur yang bisa mengakses halaman ini.");
        }
        $this->lessons = $course->lessons->sortBy("order");
    }

    public function addLesson()
    {
        $this->validate();

        $this->course->lessons()->create([
            "title" => $this->title,
            "video_url" => $this->video_url,
            "content" => $this->content,
            "order" => $this->order ?: $this->course->lessons->count() + 1,
        ]);

        $this->reset(["title", "video_url", "content", "order"]);
        $this->lessons = $this->course->lessons->sortBy("order");
        session()->flash("message", "Lesson berhasil ditambahkan!");
    }

    public function render()
    {
        return view("livewire.lesson.lesson-index");
    }
}
