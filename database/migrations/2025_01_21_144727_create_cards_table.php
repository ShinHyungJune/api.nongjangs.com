<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('number')->nullable()->comment('카드번호');
            $table->string('name')->nullable()->comment('카드사명');
            $table->text('billing_key')->nullable()->comment('빌링키');
            $table->string('color')->comment('색깔')->default('#000');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
