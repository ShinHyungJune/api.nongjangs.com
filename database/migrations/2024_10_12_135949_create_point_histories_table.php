<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('point_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('cascade');
            $table->integer('type');
            $table->boolean('increase');
            $table->integer('point');
            $table->text("memo")->nullable();
            $table->unsignedBigInteger('point_current')->default(0)->comment('남은 포인트');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('point_histories');
    }
};
