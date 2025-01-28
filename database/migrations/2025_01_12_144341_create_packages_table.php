<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('count')->comment('회차');
            $table->date('will_delivery_at')->nullable()->comment('도착예정일');
            $table->boolean('tax')->default(0)->comment('과세여부');
            $table->date('start_pack_wait_at')->nullable()->comment('구성대기 시작일');
            $table->date('finish_pack_wait_at')->nullable()->comment('구성대기 종료일');
            $table->date('start_pack_at')->nullable()->comment('품목구성 시작일');
            $table->date('finish_pack_at')->nullable()->comment('품목구성 종료일');
            $table->date('start_delivery_ready_at')->nullable()->comment('배송준비 시작일');
            $table->date('finish_delivery_ready_at')->nullable()->comment('배송준비 종료일');
            $table->date('start_will_out_at')->nullable()->comment('출고예정 시작일');
            $table->date('finish_will_out_at')->nullable()->comment('출고예정 종료일');
            $table->boolean('alert_pack')->default(0)->comment('품목구성 알림여부');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
