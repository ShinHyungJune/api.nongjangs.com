<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade');
            // $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->boolean('open')->default(true)->comment('공개여부');
            $table->unsignedBigInteger('count_view')->default(0);
            $table->unsignedBigInteger('count_order')->default(0);
            $table->string('title')->index()->comment('제목');
            $table->text('description')->nullable()->comment('내용');
            $table->text('summary')->nullable()->comment('요약 (메인 카테고리 추천 시 노출할 내용)');
            $table->unsignedBigInteger('price')->comment('최종가');
            $table->unsignedBigInteger('price_discount')->default(0)->comment('할인가');
            $table->unsignedBigInteger('price_origin')->comment('본래가');
            $table->unsignedBigInteger('price_delivery')->default(0)->comment('택배비');
            $table->boolean('pop')->default(0)->comment('인기여부');
            $table->boolean('special')->default(0)->comment('특가여부');
            $table->boolean('recommend')->default(0)->comment('MD 추천상품 여부');
            $table->boolean('empty')->default(0)->comment('품절여부');
            $table->string('duration')->nullable()->comment('제작기간');
            $table->string('texture')->nullable()->comment('재질');
            $table->integer('type_delivery')->nullable()->comment('배송방법');
            $table->string('creator')->default('언더독')->comment('제조사');
            $table->string('case')->nullable()->comment('케이스');
            $table->string('way_to_create')->nullable()->comment('작업방식');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
