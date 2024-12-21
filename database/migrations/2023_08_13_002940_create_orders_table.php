<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('guest_id')->nullable()->comment('비회원 고유번호');
            $table->unsignedBigInteger('price_products')->default(0)->comment('상품가격');
            $table->unsignedBigInteger('price_delivery')->default(0)->comment('배송비');
            $table->unsignedBigInteger('price_coupon_discount')->default(0)->comment('쿠폰할인금액');
            
            $table->string("imp_uid")->unique()->nullable()->comment('주문번호 (아임포트)');
            $table->string("merchant_uid")->nullable()->unique()->index()->comment('주문번호 (내부)');

            // 주문자
            $table->foreignId("user_id")->nullable()->constrained('users')->onDelete('cascade');
            $table->string("user_name")->nullable();
            $table->string("user_email")->nullable();
            $table->string("user_contact")->nullable();

            // 결제수단
            $table->foreignId("pay_method_id")->nullable()->constrained('pay_methods')->onDelete('cascade');
            $table->string("pay_method_name")->nullable()->comment('결제수단명');
            $table->string("pay_method_pg")->nullable()->comment('결제수단 pg');
            $table->string("pay_method_method")->nullable()->comment('결제수단 방법');

            // 가상계좌
            $table->string("vbank_num")->nullable()->comment('가상계좌 계좌번호');
            $table->string("vbank_name")->nullable()->comment('가상계좌 은행명');
            $table->string("vbank_date")->nullable()->comment('가상계좌 입금기한');

            // 주문자 정보
            $table->string("buyer_name")->nullable()->comment('주문자 이름');
            $table->string("buyer_contact")->nullable()->comment('주문자 연락처');
            $table->string("buyer_address")->nullable()->comment('주문자 주소');
            $table->string("buyer_address_detail")->nullable()->comment('주문자 상세주소');
            $table->string("buyer_address_zipcode")->nullable()->comment('주문자 우편번호');

            // 배송지 정보
            $table->string("delivery_name")->nullable()->comment('수취인 이름');
            $table->string("delivery_contact")->nullable()->comment('수취인 연락처');
            $table->string("delivery_address")->nullable()->comment('수취인 주소');
            $table->string("delivery_address_detail")->nullable()->comment('수취인 상세주소');
            $table->string("delivery_address_zipcode")->nullable()->comment('수취인 우편번호');
            $table->string("delivery_requirement")->nullable()->comment('배송요청사항');
            $table->integer("type_delivery")->nullable()->comment('택배 방법');

            $table->integer('point_use')->default(0)->comment('사용한 마일리지');
            $table->boolean('agree_open')->default(false)->comment('주문제품 시안파일 등의 정보노출 동의여부');

            $table->unsignedBigInteger("price")->nullable()->comment('결제금액');
            $table->integer("state")->default(\App\Enums\StateOrder::BEFORE_PAYMENT)->comment('결제상태');
            $table->text("memo")->nullable()->comment('메모');
            $table->text("reason")->nullable()->comment('결제실패사유');
            $table->boolean("process_success")->default(0)->comment('결제완료처리여부');
            $table->boolean('process_record')->default(0)->comment('결제 대기 또는 성공 후 관련내용 기록처리여부');
            // 환불계좌
            $table->string("refund_owner")->nullable()->comment('환불계좌 예금주');
            $table->string("refund_bank")->nullable()->comment('환불계좌 은행명');
            $table->string("refund_account")->nullable()->comment('환불계좌 계좌번호');
            $table->text("reason_refund")->nullable()->comment('환불사유');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.uct_unlo
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
