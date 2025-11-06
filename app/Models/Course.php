<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory;

    protected $fillable = [
        "user_id",
        "title",
        "description",
        "price",
        "is_published",
        "thumbnail",
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy("order");
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
