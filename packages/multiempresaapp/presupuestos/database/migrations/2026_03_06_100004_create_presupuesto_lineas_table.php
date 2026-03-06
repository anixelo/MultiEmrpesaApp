<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presupuesto_lineas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('presupuesto_id')->constrained('presupuestos')->cascadeOnDelete();
            $table->foreignId('servicio_id')->nullable()->constrained('servicios')->nullOnDelete();
            $table->integer('orden')->default(0);
            $table->string('concepto');
            $table->decimal('cantidad', 10, 2)->default(1);
            $table->decimal('precio_unitario', 10, 2);
            $table->string('descuento_tipo')->nullable(); // 'porcentaje' or 'importe'
            $table->decimal('descuento_valor', 10, 2)->nullable();
            $table->decimal('base_imponible', 12, 2)->default(0);
            $table->decimal('iva_tipo', 5, 2)->default(21);
            $table->decimal('iva_cuota', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presupuesto_lineas');
    }
};
