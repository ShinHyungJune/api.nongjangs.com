<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('counts', function (Blueprint $table) {
            $table->id();
            $table->integer('sum_weight');
            $table->integer('sum_store');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counts');
    }
};
