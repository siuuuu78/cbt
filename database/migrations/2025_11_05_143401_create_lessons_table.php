<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("lessons", function (Blueprint $table) {
            $table->id();
            $table->foreignId("course_id")->constrained()->onDelete("cascade");
            $table->string("title");
            $table->string("video_url");
            $table->text("content")->nullable();
            $table->integer("duration")->default(0); // detik
            $table->integer("order")->default(0);
            $table->boolean("is_locked")->default(false);
            $table->text("lock_message")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("lessons");
    }
};
