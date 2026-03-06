<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presupuestos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->string('numero');
            $table->date('fecha');
            $table->string('estado')->default('borrador');
            $table->decimal('subtotal_bruto', 12, 2)->default(0);
            $table->decimal('subtotal_descuentos', 12, 2)->default(0);
            $table->decimal('total_base_imponible', 12, 2)->default(0);
            $table->decimal('total_iva', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->text('notas')->nullable();
            $table->date('validez_hasta')->nullable();
            $table->string('forma_pago')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('token_publico')->unique();
            $table->timestamp('enviado_en')->nullable();
            $table->timestamp('visto_en')->nullable();
            $table->timestamp('aceptado_en')->nullable();
            $table->timestamp('rechazado_en')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presupuestos');
    }
};
