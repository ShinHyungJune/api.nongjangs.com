<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('estimates', function (Blueprint $table) {
            $table->id();
            $table->string('email')->comment('이메일');
            $table->string('name')->comment('이름');
            $table->string('contact')->comment('연락처');
            $table->string('title')->comment('제목');
            $table->text('description')->comment('내용');
            $table->string('budget')->comment('예산');
            $table->string('count')->nullable()->comment('필수상품개수');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('estimates');
    }
};
