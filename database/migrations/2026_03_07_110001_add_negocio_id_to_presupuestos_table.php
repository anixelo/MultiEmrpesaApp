<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('presupuestos', function (Blueprint $table) {
            $table->foreignId('negocio_id')->nullable()->after('empresa_id')->constrained('empresas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('presupuestos', function (Blueprint $table) {
            $table->dropForeign(['negocio_id']);
            $table->dropColumn('negocio_id');
        });
    }
};
