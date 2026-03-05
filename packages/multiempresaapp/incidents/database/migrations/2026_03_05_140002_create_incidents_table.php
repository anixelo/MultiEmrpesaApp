<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('status')->default('open'); // open, in_review, in_progress, resolved, closed
            $table->string('priority')->default('media'); // baja, media, alta, urgente
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('incidents');
    }
};
