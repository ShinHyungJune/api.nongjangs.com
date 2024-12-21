<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTaxColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('tax_business_number')->nullable()->comment('사업자번호');
            $table->string('tax_company_title')->nullable()->comment('업체명');
            $table->string('tax_company_president')->nullable()->comment('대표자명');
            $table->string('tax_company_type')->nullable()->comment('업종');
            $table->string('tax_company_category')->nullable()->comment('업태');
            $table->string('tax_email')->nullable()->comment('세금계산서 발행메일');
            $table->string('tax_name')->nullable()->comment('세금계산서 담당자 이름');
            $table->string('tax_contact')->nullable()->comment('세금계산서 담당자 연락처');
            $table->string('tax_address')->nullable()->comment('세금계산서 업체주소');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
