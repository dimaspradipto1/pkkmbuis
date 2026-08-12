<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evaluasi_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluasi_menu_id')->constrained('evaluasi_menus')->onDelete('cascade');
            $table->string('question_key'); // e.g. 'q1', 'q2', ...
            $table->text('pertanyaan');
            $table->timestamps();

            $table->unique(['evaluasi_menu_id', 'question_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi_questions');
    }
};
