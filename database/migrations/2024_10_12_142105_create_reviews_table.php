<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('preset_product_id')->constrained('preset_product')->onDelete('cascade');

            $table->boolean('best')->default(0)->comment('베스트 여부');
            $table->integer('point')->comment('부여받은 포인트');
            $table->text('description')->nullable()->comment('내용');
            $table->boolean('photo')->default(0)->comment('사진리뷰 여부');
            $table->integer('score')->default(1)->comment('점수');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};
