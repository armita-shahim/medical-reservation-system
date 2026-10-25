<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('availability_exceptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->foreignId('hospital_id')->nullable()->constrained('hospitals')->restrictOnDelete();
            $table->timestampTz('start_at');
            $table->timestampTz('end_at');
            $table->string('reason')->nullable();
            $table->timestampsTz();
            $table->index(['doctor_id', 'start_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('availability_exceptions');
    }
};
