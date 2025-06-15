<?php

use App\Enums\DeliveryCompany;
use App\Enums\TypeDelivery;
use App\Enums\TypeDeliveryPrice;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('delivery_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('type_delivery')->comment('배송유형')->default(TypeDelivery::FREE);
            $table->foreignId('delivery_company_id')->comment('택배사')->constrained('delivery_companies')->cascadeOnDelete('set null');;
            $table->integer('type_delivery_price')->comment('배송비 유형')->default(TypeDeliveryPrice::STATIC);
            $table->unsignedBigInteger('price_delivery')->comment('배송비')->nullable();
            $table->text('prices_delivery')->nullable()->comment('수량별 차등 배송비');
            $table->unsignedBigInteger('min_price_for_free_delivery_price')->nullable()->comment('무료배송 최소주문금액');
            $table->boolean('can_delivery_far_place')->default(0)->comment('제주/도서산간 배송가능여부');
            $table->text('ranges_far_place')->nullable()->comment('제주/도서산간 지역 설정 (["zipcode_start" => "", "zipcode_end" => ""]');
            // $table->unsignedBigInteger('delivery_price_far_place')->nullable()->comment('제주도서산간 배송비');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_settings');
    }
};
