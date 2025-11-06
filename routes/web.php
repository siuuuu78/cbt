<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Halaman publik
Route::view("/", "welcome")->name("welcome");

// Halaman yang butuh autentikasi
Route::middleware(["auth", "verified"])->group(function () {
    // Dashboard default
    Route::view("/dashboard", "dashboard")->name("dashboard");

    // Profile
    Route::view("/profile", "profile")->name("profile");

    // === HALAMAN INSTRUKTUR ===
    Route::prefix("instructor")->group(function () {
        // Course
        Route::view("/courses", "instructor.courses.index")->name(
            "courses.index",
        );
        Route::view("/courses/create", "instructor.courses.create")->name(
            "courses.create",
        );
        Route::view("/courses/{course}/edit", "instructor.courses.edit")->name(
            "courses.edit",
        );

        // Lesson
        Route::view(
            "/courses/{course}/lessons",
            "instructor.lessons.index",
        )->name("lessons.index");

        // Quiz
        Route::view("/lessons/{lesson}/quiz", "instructor.quiz.form")->name(
            "lessons.quiz",
        );
    });

    // === HALAMAN ADMIN ===
    Route::middleware(["role:admin"])
        ->prefix("admin")
        ->group(function () {
            Route::view("/instructors", "admin.instructor-manager")->name(
                "admin.instructors",
            );

            // Aksi promosikan/cabut instruktur
            Route::post("/instructors/{user}/promote", function (
                \App\Models\User $user,
            ) {
                $user->assignRole("instructor");
                return back()->with(
                    "message",
                    "User berhasil dijadikan instruktur.",
                );
            })->name("admin.instructors.promote");

            Route::delete("/instructors/{user}/revoke", function (
                \App\Models\User $user,
            ) {
                $user->removeRole("instructor");
                return back()->with(
                    "message",
                    "Status instruktur berhasil dicabut.",
                );
            })->name("admin.instructors.revoke");
        });

    // === HALAMAN SISWA (Student) ===
    // Contoh: daftar kursus publik
    Route::view("/courses", "student.courses.index")->name(
        "student.courses.index",
    );
    Route::view("/courses/{course}", "student.courses.show")->name(
        "student.courses.show",
    );
    Route::view(
        "/courses/{course}/lessons/{lesson}",
        "student.lessons.show",
    )->name("student.lessons.show");

    // Ambil kuis
    Route::view("/lessons/{lesson}/take-quiz", "student.quiz.take")->name(
        "student.quiz.take",
    );
    Route::view("/lessons/{lesson}/quiz-result", "student.quiz.result")->name(
        "student.quiz.result",
    );
});

// Autentikasi (login, register, dll)
require __DIR__ . "/auth.php";
