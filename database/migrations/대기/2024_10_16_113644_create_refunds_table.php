<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('preset_id')->constrained('presets')->onDelete('cascade');
            $table->string('category')->comment('유형');
            $table->string('title')->comment('제목');
            $table->text('description')->nullable()->comment('내용');
            $table->text('reason_deny')->nullable()->comment('거절사유');
            $table->string('state')->default(\App\Enums\StateRefund::WAIT)->comment('상태');
            $table->dateTime('processed_at')->nullable()->comment('처리완료일자');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
