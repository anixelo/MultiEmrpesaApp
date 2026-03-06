<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presupuestos_configuracion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->unique()->constrained('companies')->cascadeOnDelete();
            $table->decimal('iva_defecto', 5, 2)->default(21);
            $table->string('prefijo')->nullable()->default('PRE');
            $table->integer('siguiente_numero')->default(1);
            $table->integer('validez_dias')->nullable()->default(30);
            $table->string('forma_pago_defecto')->nullable();
            $table->text('observaciones_defecto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presupuestos_configuracion');
    }
};
