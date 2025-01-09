<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alarms', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('contact')->nullable();
            $table->string('email')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');

            $table->foreignId('preset_product_id')->nullable()->constrained('preset_product')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('cascade');
            $table->foreignId('preset_id')->nullable()->constrained('presets')->onDelete('cascade');
            $table->foreignId('qna_id')->nullable()->constrained('qnas')->onDelete('cascade');
            $table->foreignId('prototype_id')->nullable()->constrained('prototypes')->onDelete('cascade');
            $table->foreignId('feedback_id')->nullable()->constrained('feedback')->onDelete('cascade');
            $table->foreignId('estimate_id')->nullable()->constrained('estimates')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alarms');
    }
};
