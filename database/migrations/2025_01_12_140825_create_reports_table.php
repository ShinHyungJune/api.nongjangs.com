<?php

use App\Enums\StateReport;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->integer('state')->default(StateReport::WAIT);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('report_category_id')->constrained('report_categories');
            $table->morphs('reportable');
            $table->text('description')->nullable()->comment('내용');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
