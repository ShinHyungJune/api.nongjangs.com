<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliveryStartedAtColumnToPresetProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('preset_product', function (Blueprint $table) {
            $table->date('delivery_started_at')->nullable()->comment('발송처리일');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('preset_product', function (Blueprint $table) {
            //
        });
    }
}
