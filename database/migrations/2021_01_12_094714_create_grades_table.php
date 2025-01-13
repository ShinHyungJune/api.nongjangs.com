<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedFloat('ratio_refund')->comment('적립률');
            $table->integer('min_price')->comment('구매금액');
            $table->integer('min_count_package')->comment('꾸러미 이용회차');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
