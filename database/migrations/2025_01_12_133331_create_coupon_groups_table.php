<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('coupon_groups', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('제목');
            $table->integer('moment')->nullable()->comment('특정 시점에 발급되는 쿠폰 (1 - UPDATE_PROFILE 프로필 업데이트 | 2 - GRADE 등급혜택 | 3 - BIRTHDAY 생일쿠폰 | 4 - FIRST_ORDER 첫구매)');
            $table->integer('type')->comment('유형 (1 - ALL 모든상품 | 2 - PACKAGE 꾸러미 | 3 - PRODUCT 직거래상품 | 4 - DELIVERY 배송비)');
            $table->integer('type_package')->nullable()->comment('꾸러미 유형 (1 - SINGLE 싱글 | 2 - BUNGLE 벙글');
            $table->boolean('all_product')->nullable()->comment('모든 상품 해당여부');
            $table->integer('target')->comment('발급대상 (1 - ALL 전체회원 | 2 - GRADE 고객등급 | 3 - ORDER_HISTORY 구매이력 | 4 - PERSONAL 개인회원)');
            $table->foreignId('grade_id')->nullable()->comment('등급');
            $table->unsignedBigInteger('min_order')->nullable()->comment('최소 구매이력수 (최근 3개월 n회 구매한 사람에게만 적용하는 쿠폰)');
            $table->integer('type_discount')->comment('할인유형 (1 - NUMBER 원 | 2 - RATIO %)');
            $table->unsignedBigInteger('value')->comment('할인값');
            $table->unsignedBigInteger('max_price_discount')->comment('최대할인금액');
            $table->unsignedBigInteger('min_price_order')->comment('최소주문금액');
            $table->integer('type_expire')->comment('만료유형 (1 - SPECIFIC 특정기간 | 2 - FROM_DOWNLOAD 다운로드일 기준');
            $table->dateTime('started_at')->nullable()->comment('시작일자');
            $table->dateTime('finished_at')->nullable()->comment('종료일자');
            $table->unsignedBigInteger('days')->nullable()->comment('유효기간(다운로드일 기준)');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_groups');
    }
};
