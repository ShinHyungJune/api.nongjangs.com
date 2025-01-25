<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stop_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_setting_id')->constrained('package_settings')->onDelete('cascade');
            $table->text('memo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stop_histories');
    }
};
