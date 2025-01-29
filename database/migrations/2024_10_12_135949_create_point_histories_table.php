<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('point_histories', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('point_id')->constrained('points')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('point_historiable_id')->nullable();
            $table->string('point_historiable_type')->nullable();
            $table->integer('type');
            $table->boolean('increase')->comment('증감여부');
            $table->integer('point')->comment('증감 포인트');
            $table->integer('point_leave')->comment('남은 포인트');
            $table->text("memo")->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('point_histories');
    }
};
