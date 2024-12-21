<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('presets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('cascade');
            $table->foreignId('cart_id')->nullable()->constrained('carts');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('guest_id')->nullable()->comment('게스트 고유번호');
            $table->integer('count')->default(1);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('presets');
    }
};
