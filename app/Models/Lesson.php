<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    /** @use HasFactory<\Database\Factories\LessonFactory> */
    use HasFactory;

    protected $fillable = [
        "course_id",
        "title",
        "video_url",
        "content",
        "duration",
        "order",
        "is_locked",
        "lock_message",
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function quiz()
    {
        return $this->hasOne(Quiz::class);
    }

    public function progresses()
    {
        return $this->hasMany(LessonProgress::class);
    }
}
