<?php

use App\Enums\StatePresetProduct;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('preset_products', function (Blueprint $table) {
            $table->id();
            $table->string('state')->default(StatePresetProduct::BEFORE_PAYMENT)->comment('상태');
            $table->foreignId('preset_id')->constrained('presets');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('option_id')->constrained('options');
            $table->foreignId('coupon_id')->constrained('coupons');

            $table->string('product_title')->comment('상품명');
            $table->unsignedBigInteger('product_price')->comment('상품판매가');
            $table->unsignedBigInteger('product_price_origin')->comment('상품정가');
            $table->unsignedBigInteger('count')->comment('개수');
            $table->string('option_title')->comment('옵션명');
            $table->unsignedBigInteger('option_price')->comment('옵션가격');
            $table->integer('option_type')->comment('옵션유형');

            $table->string('delivery_name')->nullable()->comment('수취인 이름');
            $table->string('delivery_contact')->nullable()->comment('수취인 연락처');
            $table->string('delivery_address')->nullable()->comment('주소');
            $table->string('delivery_address_detail')->nullable()->comment('상세주소');
            $table->string('delivery_address_zipcode')->nullable()->comment('우편번호');
            $table->text('delivery_requirement')->nullable()->comment('배송요청사항');
            $table->string('delivery_number')->nullable()->comment('운송장번호');
            $table->integer('delivery_company')->nullable()->comment('택배사');
            $table->date('delivery_at')->nullable()->comment('배송완료일자');
            $table->unsignedBigInteger('price_coupon')->comment('쿠폰적용금액')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preset_products');
    }
};
