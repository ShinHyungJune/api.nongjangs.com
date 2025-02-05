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

            $table->text("transaction_id")->nullable()->unique()->index()->comment('주문번호 (포트원)');
            $table->string("payment_id")->nullable()->unique()->index()->comment('주문번호');

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
            $table->boolean("pay_method_external")->nullable()->comment('결제수단 내부결제여부');
            $table->string("pay_method_channel_key")->nullable()->comment('결제수단 채널키');

            // 카드정보
            $table->string('card_number')->nullable()->comment('카드번호');
            $table->string('card_expiry_year')->nullable()->comment('만료년도');
            $table->string('card_expiry_month')->nullable()->comment('만료월');
            $table->string('card_password')->nullable()->comment('비밀번호');
            $table->string('card_name')->nullable()->comment('카드사명');
            $table->string('card_birth_or_business_number')->nullable()->comment('생년월일 또는 사업자번호');
            $table->text('card_billing_key')->nullable()->comment('빌링키');
            $table->string('card_color')->nullable()->comment('색깔')->default('#000');

            // 가상계좌
            $table->string("vbank_num")->nullable()->comment('가상계좌 계좌번호');
            $table->string("vbank_name")->nullable()->comment('가상계좌 은행명');
            $table->string("vbank_date")->nullable()->comment('가상계좌 입금기한');

            // 주문자 정보
            $table->string("buyer_name")->nullable()->comment('주문자 이름');
            $table->string("buyer_contact")->nullable()->comment('주문자 연락처');

            // 배송지 정보
            $table->string("delivery_name")->nullable()->comment('수취인 이름');
            $table->string("delivery_contact")->nullable()->comment('수취인 연락처');
            $table->string("delivery_address")->nullable()->comment('수취인 주소');
            $table->string("delivery_address_detail")->nullable()->comment('수취인 상세주소');
            $table->string("delivery_address_zipcode")->nullable()->comment('수취인 우편번호');
            $table->string("delivery_requirement")->nullable()->comment('배송요청사항');

            $table->integer('point_use')->default(0)->comment('사용한 마일리지');

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

            $table->dateTime("success_at")->nullable()->comment('결제성공일자');

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
