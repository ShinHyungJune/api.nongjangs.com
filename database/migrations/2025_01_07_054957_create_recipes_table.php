<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title')->comment('제목');
            $table->text('description')->nullable()->comment('내용');
            $table->text('materials')->nullable()->comment('재료');
            $table->text('seasonings')->nullable()->comment('양념');
            $table->text('ways')->nullable()->comment('조리순서');
            $table->integer('count_view')->default(0)->comment('조회수');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
