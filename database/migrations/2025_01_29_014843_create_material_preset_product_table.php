<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialPresetProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_preset_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('materials')->onDelete('cascade');
            $table->foreignId('preset_product_id')->constrained('preset_product')->onDelete('cascade');
            $table->unsignedBigInteger('price')->comment('판매가');
            $table->unsignedBigInteger('price_origin')->comment('정가');
            $table->string('unit')->comment('단위');
            $table->unsignedBigInteger('count')->comment('개수');
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
        Schema::dropIfExists('material_preset_product');
    }
}
