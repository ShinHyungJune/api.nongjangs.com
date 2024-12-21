<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNeedAlertDeliveryColumnToPresetProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('preset_product', function (Blueprint $table) {
            $table->boolean('need_alert_delivery')->default(0)->comment('배송알림 필요여부');
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
