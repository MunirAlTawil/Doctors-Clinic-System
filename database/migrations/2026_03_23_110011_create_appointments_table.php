<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('specialty_id')->constrained()->cascadeOnDelete();
            $table->date('appointment_date');
            $table->time('start_time');
            $table->unsignedInteger('hours_count');
            $table->time('end_time');
            $table->decimal('hourly_rate', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('admin_profit_percent', 5, 2)->default(0);
            $table->decimal('admin_profit_amount', 10, 2)->default(0);
            $table->decimal('doctor_net_amount', 10, 2)->default(0);
            $table->string('payment_method');
            $table->string('payment_status')->default('paid');
            $table->string('status')->default('booked');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
