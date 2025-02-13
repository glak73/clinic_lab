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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('specialty');
            $table->json('working_hours'); // Используем JSON для хранения графика работы
            $table->timestamps();
        });
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->string('patient_name');
            $table->date('date');
            $table->time('time');
            $table->enum('status', ['pending', 'visited', 'cancelled']);
            $table->timestamps();

            // Добавляем уникальный индекс для предотвращения дубликатов
            $table->unique(['doctor_id', 'date', 'time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
        Schema::dropIfExists('appointments');
    }
};
