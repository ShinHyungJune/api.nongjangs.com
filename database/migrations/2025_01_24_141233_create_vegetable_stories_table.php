<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vegetable_stories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('package_id')->nullable()->constrained('packages')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade');
            $table->foreignId('preset_product_id')->nullable()->constrained('preset_product')->onDelete('cascade');
            $table->foreignId('recipe_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->text('description')->nullable()->comment('내용');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vegetable_stories');
    }
};
