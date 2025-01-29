<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('package_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->comment('꾸러미 이름');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('card_id')->nullable()->constrained('cards');
            $table->foreignId('delivery_id')->nullable()->constrained('deliveries');
            $table->string('type_package')->comment('꾸러미 유형');
            $table->integer('term_week')->comment('주기 (몇주에 한번 배송)');
            $table->boolean('active')->default(1)->comment('활성여부');
            $table->text('reason')->nullable()->comment('중단사유');
            $table->string('and_so_on')->nullable()->comment('중단사유 기타 직접입력');
            $table->string('memo')->nullable()->comment('메모');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_settings');
    }
};
