<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->foreignId('hospital_id')->constrained('hospitals')->restrictOnDelete();
            $table->text('rrule');
            $table->time('start_time');
            $table->time('end_time');
            $table->date('valid_from');
            $table->date('valid_until')->nullable();
            $table->smallInteger('slot_duration_minutes');
            $table->boolean('is_active')->default(true);
            $table->timestampsTz();
            $table->index(['doctor_id', 'hospital_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};
