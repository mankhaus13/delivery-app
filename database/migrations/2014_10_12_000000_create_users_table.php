<?php

use App\Models\Enums\User\UserPosition;
use App\Models\Enums\User\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', static function (Blueprint $table) {
            $table->id();
            $table->string('password', 60);
            //айдишник в ЕРП
            $table->char('external_id', 36);
            //todo: возможно надо хранить телефоны в едином формате
            $table->string('phone')->unique();
            //для пушей
            $table->string('device_token')->nullable();
            $table->string('first_name');
            $table->string('second_name');
            $table->string('surname');
            //роль в системе. влияет на допуск к различным частям
            $table->enum('role', UserRole::values())->default(UserRole::Expeditor->value);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
