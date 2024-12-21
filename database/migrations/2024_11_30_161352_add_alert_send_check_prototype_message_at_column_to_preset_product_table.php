<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlertSendCheckPrototypeMessageAtColumnToPresetProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('preset_product', function (Blueprint $table) {
            $table->dateTime('alert_send_check_prototype_message_at')->nullable()->comment('시안확정요청 보낸일자');
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
