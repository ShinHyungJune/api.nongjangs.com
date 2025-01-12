<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('delivery_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('type_delivery');
            $table->integer('delivery_company');
            $table->integer('type_delivery_price');
            $table->unsignedBigInteger('price_delivery');
            $table->text('prices_delivery');
            $table->unsignedBigInteger('min_price_for_free_delivery_price');
            $table->boolean('can_delivery_far_place')->default(0);
            $table->unsignedBigInteger('delivery_price_far_place')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_settings');
    }
};
