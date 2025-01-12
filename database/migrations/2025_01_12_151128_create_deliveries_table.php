<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('main')->comment('기본배송지 여부');
            $table->string('title')->comment('배송지명');
            $table->string('name')->comment('수취인명');
            $table->string('contact')->comment('연락처');
            $table->string('address')->comment('주소');
            $table->string('address_detail')->comment('상세주소');
            $table->string('address_zipcode')->comment('우편번호');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
