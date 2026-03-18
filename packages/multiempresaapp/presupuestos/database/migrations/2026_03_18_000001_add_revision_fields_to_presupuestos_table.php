<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presupuestos', function (Blueprint $table) {
            $table->timestamp('pendiente_revision_en')->nullable()->after('rechazado_en');
            $table->timestamp('validado_en')->nullable()->after('pendiente_revision_en');
            $table->text('nota_revision')->nullable()->after('validado_en');
            $table->unsignedBigInteger('revisado_por')->nullable()->after('nota_revision');
            $table->foreign('revisado_por')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('presupuestos', function (Blueprint $table) {
            $table->dropForeign(['revisado_por']);
            $table->dropColumn(['pendiente_revision_en', 'validado_en', 'nota_revision', 'revisado_por']);
        });
    }
};
