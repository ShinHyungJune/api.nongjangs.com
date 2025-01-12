<?php

use App\Enums\StateOption;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->integer('type')->comment('유형');
            $table->integer('state')->default(StateOption::ONGOING)->comment('판매상태');
            $table->string('title')->comment('제목');
            $table->unsignedBigInteger('price')->comment('가격');
            $table->unsignedBigInteger('count')->comment('재고')->default(99999);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('options');
    }
};
