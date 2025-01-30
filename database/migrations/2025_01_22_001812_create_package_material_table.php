<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageMaterialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_material', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            $table->foreignId('material_id')->constrained('materials')->onDelete('cascade');
            $table->string('type')->comment('유형');
            $table->unsignedBigInteger('value')->comment('값');
            $table->unsignedBigInteger('count')->comment('수량')->default(1);
            $table->string('unit')->comment('단위');
            $table->unsignedBigInteger('price_origin')->comment('정가');
            $table->unsignedBigInteger('price')->comment('판매가');
            $table->index(['package_id', 'material_id']);
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
        Schema::dropIfExists('package_material');
    }
}
