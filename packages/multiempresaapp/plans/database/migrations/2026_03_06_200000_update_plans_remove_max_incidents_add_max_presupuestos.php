<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            if (Schema::hasColumn('plans', 'max_incidents')) {
                $table->dropColumn('max_incidents');
            }
            if (!Schema::hasColumn('plans', 'max_presupuestos')) {
                $table->unsignedInteger('max_presupuestos')->default(0)->after('max_users');
            }
            if (!Schema::hasColumn('plans', 'has_tasks')) {
                $table->boolean('has_tasks')->default(false)->after('max_presupuestos');
            }
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            if (!Schema::hasColumn('plans', 'max_incidents')) {
                $table->unsignedInteger('max_incidents')->default(10);
            }
            if (Schema::hasColumn('plans', 'max_presupuestos')) {
                $table->dropColumn('max_presupuestos');
            }
            if (Schema::hasColumn('plans', 'has_tasks')) {
                $table->dropColumn('has_tasks');
            }
        });
    }
};
