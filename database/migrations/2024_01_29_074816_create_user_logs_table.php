<?php

use App\Models\Enums\User\ActionsToLog;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_logs', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('expeditor_id')->constrained('users', 'id')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('action', ActionsToLog::values());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_logs');
    }
};
