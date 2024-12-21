<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_group_id')->nullable()->constrained('coupon_groups')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->integer('count_view')->default(0)->comment('조회수');
            $table->date('started_at')->comment('시작일자');
            $table->date('finished_at')->comment('종료일자');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
};
