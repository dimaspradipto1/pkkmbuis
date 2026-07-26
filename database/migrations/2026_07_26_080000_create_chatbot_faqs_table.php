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
        Schema::create('chatbot_faqs', function (Blueprint $table) {
            $table->id();
            $table->string('pertanyaan');
            $table->text('jawaban');
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        if (Schema::hasTable('dokumens') && !Schema::hasColumn('dokumens', 'link_wa_group')) {
            Schema::table('dokumens', function (Blueprint $table) {
                $table->text('link_wa_group')->nullable()->after('link_rundown');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_faqs');

        if (Schema::hasTable('dokumens') && Schema::hasColumn('dokumens', 'link_wa_group')) {
            Schema::table('dokumens', function (Blueprint $table) {
                $table->dropColumn('link_wa_group');
            });
        }
    }
};
