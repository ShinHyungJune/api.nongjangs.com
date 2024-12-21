<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('coupon_groups', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('ratio_discount');
            $table->integer('duration');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupon_groups');
    }
};
