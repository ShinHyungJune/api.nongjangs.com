<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('prototypes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->boolean('confirmed')->default(false)->comment('확정여부');
            $table->foreignId('preset_product_id')->constrained('preset_product')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('prototypes');
    }
};
