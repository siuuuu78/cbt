<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DevelopmentSeeder extends Seeder
{
    public function run()
    {
        // === 1. Buat Role ===
        Role::firstOrCreate(["name" => "admin"]);
        Role::firstOrCreate(["name" => "instructor"]);
        Role::firstOrCreate(["name" => "student"]);

        // === 2. Buat Admin ===
        $admin = User::factory()->create([
            "name" => "Admin Utama",
            "email" => "admin@example.com",
            "password" => Hash::make("password"),
        ]);
        $admin->assignRole("admin");

        // === 3. Buat Instruktur ===
        $instructor = User::factory()->create([
            "name" => "Budi Instruktur",
            "email" => "budi@example.com",
            "password" => Hash::make("password"),
        ]);
        $instructor->assignRole("instructor");

        // === 4. Buat Student ===
        $student = User::factory()->create([
            "name" => "Siswa Biasa",
            "email" => "siswa@example.com",
            "password" => Hash::make("password"),
        ]);
        $student->assignRole("student");

        // === 5. Buat Kursus ===
        $course = Course::create([
            "user_id" => $instructor->id,
            "title" => "Pengenalan Web Development",
            "description" => "Belajar HTML, CSS, dan JavaScript dari nol.",
            "price" => 0, // gratis
            "is_published" => true,
            "thumbnail" => null,
        ]);

        // === 6. Buat Lesson ===
        $lesson1 = Lesson::create([
            "course_id" => $course->id,
            "title" => "Apa itu HTML?",
            "video_url" => "https://www.youtube.com/watch?v=UB1O30fR-EE",
            "content" =>
                '<p>HTML adalah bahasa markup untuk membuat struktur halaman web.</p><img src="https://via.placeholder.com/600x200?text=Diagram+HTML" alt="Diagram HTML">',
            "order" => 1,
            "is_locked" => false,
        ]);

        $lesson2 = Lesson::create([
            "course_id" => $course->id,
            "title" => "Dasar CSS",
            "video_url" => "https://www.youtube.com/watch?v=EdsrfQ2QE6I",
            "content" => "<p>CSS digunakan untuk styling halaman web.</p>",
            "order" => 2,
            "is_locked" => false,
        ]);

        // === 7. Buat Kuis untuk Lesson 1 ===
        $quiz = Quiz::create([
            "lesson_id" => $lesson1->id,
            "title" => "Kuis Pemahaman HTML",
            "max_attempts" => 2,
            "passing_score" => 67, // 2 dari 3
        ]);

        // Soal 1: Teks
        $q1 = Question::create([
            "quiz_id" => $quiz->id,
            "question_text" => "Apa kepanjangan dari HTML?",
            "type" => "multiple_choice",
        ]);

        Option::create([
            "question_id" => $q1->id,
            "option_text" => "Hyper Text Markup Language",
            "is_correct" => true,
        ]);
        Option::create([
            "question_id" => $q1->id,
            "option_text" => "High Text Machine Language",
            "is_correct" => false,
        ]);
        Option::create([
            "question_id" => $q1->id,
            "option_text" => "Hyperlink Text Management Language",
            "is_correct" => false,
        ]);

        // Soal 2: Gambar (contoh: pilih logo yang benar)
        $q2 = Question::create([
            "quiz_id" => $quiz->id,
            "question_text" => "Logo mana yang merupakan logo Laravel?",
            "type" => "multiple_choice",
            "image_url" => null, // opsional, bisa dikosongkan
        ]);

        Option::create([
            "question_id" => $q2->id,
            "option_text" => "",
            "image_url" =>
                "https://via.placeholder.com/100x100/FF6347/FFFFFF?text=Laravel",
            "is_correct" => true,
        ]);
        Option::create([
            "question_id" => $q2->id,
            "option_text" => "",
            "image_url" =>
                "https://via.placeholder.com/100x100/4682B4/FFFFFF?text=React",
            "is_correct" => false,
        ]);
        Option::create([
            "question_id" => $q2->id,
            "option_text" => "",
            "image_url" =>
                "https://via.placeholder.com/100x100/32CD32/FFFFFF?text=Vue",
            "is_correct" => false,
        ]);

        // === 8. Info Login ===
        $this->command->info("✅ Seeder selesai!");
        $this->command->table(
            ["Role", "Email", "Password"],
            [
                ["Admin", "admin@example.com", "password"],
                ["Instruktur", "budi@example.com", "password"],
                ["Student", "siswa@example.com", "password"],
            ],
        );
    }
}
