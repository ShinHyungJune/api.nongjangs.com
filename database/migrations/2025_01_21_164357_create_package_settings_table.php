<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('package_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('card_id')->nullable()->constrained('cards');
            $table->foreignId('delivery_id')->nullable()->constrained('deliveries');
            $table->string('type_package')->comment('꾸러미 유형');
            $table->integer('term_week')->comment('주기 (몇주에 한번 배송)');
            $table->boolean('active')->default(1)->comment('활성여부');
            $table->date('will_order_at')->nullable()->comment('다음 주문일자');
            $table->foreignId('first_package_id')->nullable()->comment('첫 패키지')->constrained('packages')->cascadeOnDelete('packages');
            $table->integer('retry')->default(0)->comment('결제 재시도 횟수');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_settings');
    }
};
