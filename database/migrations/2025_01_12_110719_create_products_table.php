<?php

use App\Enums\DeliveryCompany;
use App\Enums\StateProduct;
use App\Enums\TypeDelivery;
use App\Enums\TypeDeliveryPrice;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('state')->default(StateProduct::ONGOING);
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('farm_id')->constrained('farms');
            $table->foreignId('city_id')->constrained('cities');
            $table->foreignId('county_id')->constrained('counties');
            $table->foreignId('delivery_company_id')->comment('택배사')->constrained('delivery_companies')->cascadeOnDelete('set null');;
            $table->foreignId('delivery_company_refund_id')->comment('반품 택배사')->constrained('delivery_companies')->cascadeOnDelete('set null');;
            $table->string('uuid')->nullable()->comment('상품코드');
            $table->string('title')->comment('제목');
            $table->unsignedBigInteger('price')->comment('판매가');
            $table->unsignedBigInteger('price_origin')->comment('정가');
            $table->boolean('need_tax')->comment('과세여부')->default(0);
            $table->boolean('can_use_coupon')->comment('쿠폰사용 가능여부')->default(1);
            $table->boolean('can_use_point')->comment('적립금사용 가능여부')->default(1);
            $table->unsignedBigInteger('count_order')->comment('주문수')->default(0);
            $table->integer('type_delivery')->comment('배송유형')->default(TypeDelivery::FREE);
            $table->integer('type_delivery_price')->comment('배송비 유형')->default(TypeDeliveryPrice::STATIC);
            $table->integer('price_delivery')->comment('배송비')->nullable();
            $table->text('prices_delivery')->nullable()->comment('수량별 차등 배송비');
            $table->unsignedBigInteger('min_price_for_free_delivery_price')->comment('무료배송 최소주문금액')->default(0);
            $table->boolean('can_delivery_far_place')->comment('제주도서산간 배송가능여부')->default(0);
            $table->text('ranges_far_place')->nullable()->comment('제주/도서산간 지역 설정 (["zipcode_start" => "", "zipcode_end" => ""]');
            // $table->text('delivery_prices_far_places')->comment('제주도서산간 배송비 목록')->nullable();
            // $table->unsignedBigInteger('delivery_price_far_place')->comment('제주도서산간 배송비')->default(0);
            $table->unsignedBigInteger('delivery_price_refund')->comment('반품 배송비')->default(0);
            $table->string('delivery_address_refund')->nullable()->comment('교환/반품 배송지');
            $table->text('description')->nullable()->comment('상품상세');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
