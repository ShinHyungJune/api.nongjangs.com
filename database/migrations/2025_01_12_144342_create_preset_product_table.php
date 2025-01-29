<?php

use App\Enums\StatePresetProduct;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('preset_product', function (Blueprint $table) {
            $table->id();
            $table->string('state')->default(StatePresetProduct::BEFORE_PAYMENT)->comment('상태');
            $table->foreignId('preset_id')->constrained('presets')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products');
            $table->foreignId('package_id')->nullable()->constrained('packages');
            $table->foreignId('option_id')->nullable()->constrained('options');
            $table->foreignId('coupon_id')->nullable()->constrained('coupons');

            $table->unsignedBigInteger('price')->default(0)->comment('최종가격');
            $table->string('package_name')->nullable()->comment('꾸러미 이름');
            $table->string('package_count')->nullable()->comment('꾸러미 회차');
            $table->date('package_will_delivery_at')->nullable()->comment('꾸러미 도착예정일');
            $table->boolean('package_active')->nullable()->comment('구독활성여부 (1회성인지 정기구독인지)');
            $table->integer('package_type')->nullable()->comment('꾸러미 유형');
            $table->unsignedBigInteger('package_price')->nullable()->comment('패키지 가격');

            $table->unsignedBigInteger('products_price')->nullable()->default(0)->comment('상품 총 가격');
            $table->string('product_title')->nullable()->comment('상품명');
            $table->unsignedBigInteger('product_price')->nullable()->comment('상품판매가');
            $table->unsignedBigInteger('product_price_origin')->nullable()->comment('상품정가');
            $table->unsignedBigInteger('count')->nullable()->comment('개수');
            $table->string('option_title')->nullable()->comment('옵션명');
            $table->unsignedBigInteger('option_price')->nullable()->comment('옵션가격');
            $table->integer('option_type')->nullable()->comment('옵션유형');
            $table->unsignedBigInteger('price_coupon')->comment('쿠폰적용금액')->default(0);
            $table->unsignedBigInteger('point')->comment('배분된 적립금')->default(0);

     
            $table->string('delivery_name')->nullable()->comment('수취인 이름');
            $table->string('delivery_contact')->nullable()->comment('수취인 연락처');
            $table->string('delivery_address')->nullable()->comment('주소');
            $table->string('delivery_address_detail')->nullable()->comment('상세주소');
            $table->string('delivery_address_zipcode')->nullable()->comment('우편번호');
            $table->text('delivery_requirement')->nullable()->comment('배송요청사항');
            $table->string('delivery_number')->nullable()->comment('운송장번호');
            $table->integer('delivery_company')->nullable()->comment('택배사');
            $table->date('delivery_at')->nullable()->comment('배송완료일자');

            $table->text('reason_request_refund')->nullable()->comment('취소요청사유');
            $table->text('reason_deny_refund')->nullable()->comment('취소요청 반려사유');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preset_products');
    }
};
