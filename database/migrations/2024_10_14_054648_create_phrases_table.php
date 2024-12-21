<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('phrases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('phrase_product_category_id')->constrained('phrase_product_categories')->onDelete('cascade');
            $table->foreignId('phrase_receiver_category_id')->constrained('phrase_receiver_categories')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('count_use')->default(0)->comment('사용수');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('phrases');
    }
};
