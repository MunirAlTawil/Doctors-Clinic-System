<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctor_profiles', function (Blueprint $table) {
            $table->time('work_start_time')->nullable()->after('hourly_rate');
            $table->time('work_end_time')->nullable()->after('work_start_time');
            $table->string('off_day_1', 20)->nullable()->after('work_end_time');
            $table->string('off_day_2', 20)->nullable()->after('off_day_1');
        });
    }

    public function down(): void
    {
        Schema::table('doctor_profiles', function (Blueprint $table) {
            $table->dropColumn(['work_start_time', 'work_end_time', 'off_day_1', 'off_day_2']);
        });
    }
};
