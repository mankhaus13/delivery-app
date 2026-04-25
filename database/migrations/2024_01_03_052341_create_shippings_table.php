<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shippings', static function (Blueprint $table) {
            $table->id();
            $table->date('date'); //Дата погрузки (дата документа планирования погрузки)
            $table->foreignId('expeditor_id')->constrained('users')->onDelete('cascade'); //Экспедитор
            $table->string('window_number', 3); //Номер окна
            $table->time('time_start'); //Время начала погрузки
            $table->time('time_end'); //Время окончания погрузки
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shippings');
    }
};
