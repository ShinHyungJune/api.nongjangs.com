<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresetProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preset_product', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->nullable()->comment('고유번호');
            $table->foreignId("preset_id")->constrained('presets')->onDelete('cascade');
            $table->foreignId("product_id")->constrained('products')->onDelete('cascade');
            $table->foreignId("color_id")->nullable()->constrained('colors')->onDelete('cascade');
            $table->foreignId("size_id")->nullable()->constrained('sizes')->onDelete('cascade');

            $table->boolean('additional')->default(0)->comment('추가상품여부');
            $table->integer('count')->comment('개수');

            // 주문할 당시 정보
            $table->string('product_title')->nullable()->comment('상품명');
            $table->unsignedBigInteger('price')->nullable()->comment('최종가');
            $table->unsignedBigInteger('price_discount')->nullable()->comment('할인가');
            $table->unsignedBigInteger('price_origin')->nullable()->comment('본래가');
            $table->unsignedBigInteger('price_delivery')->nullable()->comment('배송비');
            $table->string('size_title')->nullable()->comment('사이즈명');
            $table->string('size_price')->nullable()->comment('사이즈 가격');
            $table->string('color_title')->nullable()->comment('컬러명');

            // 문구작성
            $table->string('title')->nullable()->comment('제목');
            $table->string('receiver')->nullable()->comment('받는사람');
            $table->text('description')->nullable()->comment('본문');
            $table->string('date')->nullable()->comment('제품 삽입 날짜');
            $table->string('sender')->nullable()->comment('주는사람/단체명');
            $table->string('logo_url')->nullable()->comment('로고 URL');
            $table->string('type_stamp')->nullable()->comment('직인 사용여부');
            $table->text('requirement')->nullable()->comment('요청사항');

            // 주문 후 데이터
            $table->integer('state')->default(\App\Enums\StatePresetProduct::BEFORE_PAYMENT)->comment('상태');
            $table->boolean('confirm_prototype')->default(false)->comment('시안 컨펌여부');
            $table->date('will_prototype_finished_at')->nullable()->comment('시안제작 예정일');
            $table->string('delivery_name')->nullable()->comment('수취인명');
            $table->string('delivery_contact')->nullable()->comment('연락처');
            $table->string('delivery_address')->nullable()->comment('주소');
            $table->string('delivery_address_detail')->nullable()->comment('상세주소');
            $table->string('delivery_address_zipcode')->nullable()->comment('우편번호');
            $table->string('delivery_requirement')->nullable()->comment('배송시 요청사항');
            $table->integer('type_delivery')->nullable()->comment('배송방법');

            $table->string('delivery_number')->nullable()->comment('운송장번호');
            $table->string('delivery_company')->nullable()->comment('택배사');
            $table->date('delivery_at')->nullable()->comment('배송완료일자');

            $table->index(['preset_id', 'product_id']);

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
        Schema::dropIfExists('preset_product');
    }
}
