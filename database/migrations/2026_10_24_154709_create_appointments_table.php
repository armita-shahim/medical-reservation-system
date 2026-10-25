<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->restrictOnDelete();
            $table->index('patient_id');
            $table->foreignId('doctor_id')->constrained('doctors')->restrictOnDelete();
            $table->foreignId('hospital_id')->constrained('hospitals')->restrictOnDelete();
            $table->timestampTz('start_at');
            $table->timestampTz('end_at');
            $table->string('status')->default('booked');
            $table->text('notes')->nullable();
            $table->foreignId('cancelled_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestampTz('cancelled_at')->nullable();
            $table->string('cancel_reason')->nullable();
            $table->timestampTz('reminder_sent_at')->nullable();
            $table->timestampsTz();
            $table->index(['doctor_id', 'start_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
