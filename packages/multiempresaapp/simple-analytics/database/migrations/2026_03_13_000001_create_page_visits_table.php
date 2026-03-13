<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_visits', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->string('path');
            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('referer')->nullable();
            $table->string('method', 10)->default('GET');
            $table->date('date');
            $table->timestamps();

            $table->index(['path', 'date']);
            $table->index(['ip', 'path', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_visits');
    }
};
