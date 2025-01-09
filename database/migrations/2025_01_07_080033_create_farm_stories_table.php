<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('farm_stories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('farm_id')->constrained('farms')->onDelete('cascade');
            $table->string('title')->comment('제목');
            $table->string('description')->comment('내용');
            $table->boolean('internal')->default(0)->comment('내부(농장승) 여부');
            $table->integer('count_view')->default(0)->comment('조회수');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('farm_stories');
    }
};
