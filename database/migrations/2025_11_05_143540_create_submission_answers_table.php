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
        Schema::create("submission_answers", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("submission_id")
                ->constrained("quiz_submissions")
                ->onDelete("cascade");
            $table->foreignId("question_id")->constrained();
            $table
                ->foreignId("selected_option_id")
                ->nullable()
                ->constrained("options");
            $table->boolean("is_correct");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("submission_answers");
    }
};
