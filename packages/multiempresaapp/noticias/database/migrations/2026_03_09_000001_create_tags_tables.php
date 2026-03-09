<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->string('slug', 110)->unique();
            $table->timestamps();
        });

        Schema::create('noticia_tag', function (Blueprint $table) {
            $table->foreignId('noticia_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->primary(['noticia_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('noticia_tag');
        Schema::dropIfExists('tags');
    }
};
