<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_methods', function (Blueprint $table) {
            $table->id();
            $table->string("pg")->nullable();
            $table->string("method")->nullable();
            $table->string("name")->nullable();
            $table->text('channel_key')->nullable()->comment('채널키');
            $table->integer("commission")->nullable();
            $table->boolean("used")->default(true);
            $table->boolean('external')->default(1)->comment('외부결제수단 여부');
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
        Schema::dropIfExists('pay_methods');
    }
}
