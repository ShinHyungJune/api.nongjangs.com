<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->nullable()->constrained('packages')->onDelete('cascade');
            $table->foreignId('tag_id')->nullable()->comment('tags')->constrained('grades')->onDelete('cascade');
            $table->index(['package_id', 'tag_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package_tag');
    }
}
