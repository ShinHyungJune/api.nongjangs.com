<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stop_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('stop_preset_product_id')->nullable()->comment('중단된 내역')->constrained('users')->onDelete('cascade');
            $table->string('reason')->nullable()->comment('사유');
            $table->text('and_so_on')->nullable()->comment('기타 직접입력');
            $table->text('memo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stop_histories');
    }
};
