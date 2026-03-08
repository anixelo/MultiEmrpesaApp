<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->unsignedBigInteger('promo_plan_id')->nullable()->after('active');
            $table->timestamp('promo_ends_at')->nullable()->after('promo_plan_id');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['promo_plan_id', 'promo_ends_at']);
        });
    }
};
