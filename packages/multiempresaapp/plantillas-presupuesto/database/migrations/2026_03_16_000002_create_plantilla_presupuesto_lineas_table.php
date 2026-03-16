<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plantilla_presupuesto_lineas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plantilla_id')->constrained('plantillas_presupuesto')->cascadeOnDelete();
            $table->integer('orden')->default(0);
            $table->string('concepto');
            $table->decimal('cantidad', 10, 2)->default(1);
            $table->decimal('precio_unitario', 10, 2)->default(0);
            $table->string('descuento_tipo')->nullable();
            $table->decimal('descuento_valor', 10, 2)->nullable();
            $table->decimal('iva_tipo', 5, 2)->default(21);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plantilla_presupuesto_lineas');
    }
};
