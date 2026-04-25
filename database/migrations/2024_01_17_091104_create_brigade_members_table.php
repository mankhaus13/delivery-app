<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Enums\User\UserPosition;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('brigade_members', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('brigade_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->enum('position', UserPosition::values());
            $table->string('fio', 100);
            $table->string('telephone', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brigade_members');
    }
};
