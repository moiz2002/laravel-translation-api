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
        Schema::create('translation_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('translation_id')->constrained()->onDelete('cascade');
            $table->foreignId('language_id')->constrained()->onDelete('cascade');
            $table->foreignId('tag_id')->constrained('tags')->onDelete('cascade');
            $table->string('locale', 10); // denormalized for speed
            $table->text('content');
            $table->timestamps();

            $table->unique(['translation_id', 'language_id', 'tag_id'], 'unique_translation_locale_tag');
            $table->index(['locale', 'tag_id'], 'idx_locale_tag');
            $table->index(['translation_id', 'language_id', 'tag_id'], 'idx_trans_lang_tag');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translation_entries');
    }
};
