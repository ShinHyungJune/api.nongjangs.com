<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->boolean('photo')->default(0)->comment('사진리뷰여부');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('package_id')->nullable()->constrained('packages')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade');
            $table->foreignId('preset_product_id')->nullable()->constrained('preset_product')->onDelete('cascade');
            $table->boolean('best')->comment('베스트 여부')->default(0);
            $table->integer('score')->comment('점수');
            $table->text('description')->nullable()->comment('내용');
            $table->text('reply')->nullable()->comment('관리자 답글');
            $table->dateTime('reply_at')->nullable()->comment('관리자 답글일자');
            $table->unsignedBigInteger('point')->default(0)->comment('받은 적립금');
            $table->boolean('hide')->default(0)->comment('관리자 가림여부');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
