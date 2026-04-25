<?php

use App\Models\Enums\Order\Period;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('brigades', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('expeditor_id')->constrained('users', 'id')->onDelete('cascade');
            $table->date('date'); //Дата рабочего дня;
            //Одно значение доставки: «утро», «день», «вечер»
            $table->enum('period', Period::values());
            $table->char('car_id', 6); //Автомобиль;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brigades');
    }
};
