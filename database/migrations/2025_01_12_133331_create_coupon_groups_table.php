<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('coupon_groups', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('moment');
            $table->integer('type');
            $table->integer('type_package')->nullable();
            $table->boolean('all_product')->nullable();
            $table->integer('target');
            $table->foreignId('grade_id')->nullable();
            $table->unsignedBigInteger('min_order')->nullable();
            $table->integer('type_discount');
            $table->unsignedBigInteger('value');
            $table->unsignedBigInteger('max_price_discount');
            $table->unsignedBigInteger('min_price_order');
            $table->integer('type_expire');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('finished_at')->nullable();
            $table->unsignedBigInteger('days')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_groups');
    }
};
