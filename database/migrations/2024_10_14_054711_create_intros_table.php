<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('intros', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('use')->default(1)->comment('사용여부');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('intros');
    }
};
