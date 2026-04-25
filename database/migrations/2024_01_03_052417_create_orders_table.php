<?php

use App\Models\Enums\Order\OrderStatus;
use App\Models\Enums\Order\PaymentMethod;
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
        Schema::create('orders', static function (Blueprint $table) {
            $table->id();
            //Код экспедитора (привязка к экспедитору);
            $table->foreignId('expeditor_id')->constrained('users', 'id')->onDelete('cascade');
            //причина отмены (если есть)
            $table->foreignId('cancelation_reason_id')
                ->nullable()
                ->constrained('cancelation_reasons')
                ->onDelete('no action')
                ->onUpdate('cascade');
            //причина недовоза (если есть)
            $table->foreignId('discrepancy_reason_id')
                ->nullable()
                ->constrained('bottles_discrepancy_reasons')
                ->onDelete('no action')
                ->onUpdate('cascade');
            //до какого момента ждать клиента на точке
            $table->dateTime('wait_until')->nullable();
            //время доставки по маршрутизации (с указанием границ +30 / -30 минут)
            $table->time('expected_delivery_time')->nullable();
            $table->timestamps();
            //сумма к оплате
            $table->float('total');
            //Дата отгрузки (привязка к рабочему дню);
            $table->date('shipping_date');
            //Количество тары на возврат (указанное при создании заявки)
            $table->integer('return_bottles');
            //Количество тары на возврат (реально полученные от клиента)
            $table->integer('empty_bottles');
            //предоплата
            $table->integer('prepayment')->default(0);
            //комментарий к адресу (например не стучать в дверь)
            $table->string('address_comment')->nullable();
            //способ оплаты: оплачено\оплата при получении
            $table->enum('payment_method', PaymentMethod::values());
            // айдишник в ЕРП
            $table->char('external_id', 36);
            //это номер заявки
            $table->char('number', 10);
            // статус заявки: (ждет выполнения, активная, выполнена, отменена, ждет подтверждения отмены, новая)
            $table->enum('status', OrderStatus::values())->default(OrderStatus::Pending->value);
            //Одно значение доставки: «утро», «день», «вечер»
            $table->enum('period', Period::values());
            //Адрес доставки: город, улица, дом, квартира
            $table->string('address');
            //Наименование клиента;
            $table->string('client_name');
            //Служебная информация адреса (подъезд, лифт, этаж)
            $table->string('address_extra_info');
            //комментарий к заказу (например о предпочтительном времени доставки)
            $table->string('order_comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
