<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('farm_stories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('factory_id')->constrained('factories');
            $table->string('title')->comment('제목');
            $table->string('description')->comment('내용');
            $table->integer('count_view')->default(0)->comment('조회수');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('farm_stories');
    }
};
