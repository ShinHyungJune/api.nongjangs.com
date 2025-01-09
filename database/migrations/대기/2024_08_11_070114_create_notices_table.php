<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->boolean('important')->default(0)->comment("공지여부");
            $table->string('title')->comment("제목");
            $table->text('description')->nullable()->comment("내용");
            $table->integer('count_view')->default(0)->comment("조회수");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
