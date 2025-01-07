<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pops', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('제목');
            $table->string('url')->nullable()->comment('이동 URL');
            $table->boolean('open')->default(1)->comment('노출여부');
            $table->date('started_at')->comment('노출 시작일');
            $table->date('finished_at')->comment('노출 종료일');
            $table->integer('order')->default(0)->comment('순서');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pops');
    }
}
