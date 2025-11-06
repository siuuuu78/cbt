<?php

namespace App\Http\Livewire\Course;

use Livewire\Component;
use App\Models\Course;

class CourseIndex extends Component
{
    public function mount()
    {
        if (!auth()->user()->hasRole("instructor")) {
            abort(403, "Hanya instruktur yang bisa mengakses halaman ini.");
        }
    }

    public function render()
    {
        $courses = auth()
            ->user()
            ->courses()
            ->withCount("lessons")
            ->latest()
            ->get();
        return view("livewire.course.course-index", compact("courses"));
    }
}
