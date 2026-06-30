<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lahans', function (Blueprint $table) {
            $table->json('statistik_data')->nullable()->after('insight_gemini');
            $table->text('insight_statistik')->nullable()->after('statistik_data');
        });
    }

    public function down(): void
    {
        Schema::table('lahans', function (Blueprint $table) {
            $table->dropColumn(['statistik_data', 'insight_statistik']);
        });
    }
};
