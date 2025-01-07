<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('type')->comment('유형');
            $table->text('title')->nullable()->comment('제목');
            $table->text('subtitle')->nullable()->comment('부제목');
            $table->string('url')->nullable()->comment('이동 URL');
            $table->string('button')->nullable()->comment('버튼명');
            $table->string('color_text')->nullable()->comment('글자색상');
            $table->string('color_button')->nullable()->comment('버튼색상');
            $table->date('started_at')->nullable()->comment('노출시작일자');
            $table->date('finished_at')->nullable()->comment('노출종료일자');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
