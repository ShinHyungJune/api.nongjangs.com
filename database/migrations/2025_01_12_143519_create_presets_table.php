<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('presets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable();
            $table->foreignId('cart_id')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->unsignedBigInteger('price_delivery')->comment('배송비')->default(0);
            $table->unsignedBigInteger('price')->comment('최종금액')->default(0);
            $table->unsignedBigInteger('price_total')->comment('상품 총 금액')->default(0);
            $table->unsignedBigInteger('price_discount')->comment('할인금액')->default(0);

            $table->integer('count_option_required')->comment('필수옵션 개수')->default(0);
            $table->integer('count_option_additional')->comment('추가옵션 개수')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presets');
    }
};
