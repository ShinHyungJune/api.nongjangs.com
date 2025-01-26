<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('qnas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qna_category_id')->constrained('qna_categories')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->text('answer')->nullable()->comment('답변');
            $table->dateTime('answered_at')->nullable()->comment('답변일자');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qna_categories');
    }
};
