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
        Schema::create('bodychecks', static function (Blueprint $table) {
            $table->id();
            //Дата медосмотра (дата документа планирования погрузки)
            $table->date('date');
            // Назначенное время медосмотра
            $table->time('time_start'); //Время начала погрузки
            $table->foreignId('expeditor_id')->constrained('users')->onDelete('cascade'); //Экспедитор
            $table->boolean('passed')->default(false); //Признак прохождения медосмотра
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bodychecks');
    }
};
