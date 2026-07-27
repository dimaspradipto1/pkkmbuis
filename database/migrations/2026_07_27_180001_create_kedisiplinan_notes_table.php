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
        Schema::create('kedisiplinan_notes', function (Blueprint $table) {
            $table->id();
            $table->string('category')->index(); // kedisiplinanpertama, kedisiplinankedua, kedisiplinanketiga
            $table->text('content');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kedisiplinan_notes');
    }
};
