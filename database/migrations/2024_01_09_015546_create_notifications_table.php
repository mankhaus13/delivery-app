<?php

use App\Models\Enums\Notification\NotificationOrderStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', static function (Blueprint $table) {
            $table->id();
            //текст уведомления
            $table->string('message');
            //признак "прочитано"
            $table->boolean('viewed')->default(false);
            $table->foreignId('expeditor_id')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('order_id')->constrained('orders', 'id')->onDelete('cascade');
            //уведомления приходят при изменении статусов заказа
            $table->enum('status', NotificationOrderStatus::values());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
