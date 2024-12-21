<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->text('social_id')->nullable();
            $table->string('social_platform')->nullable();

            $table->integer("type")->default(\App\Enums\TypeUser::COMMON)->comment("유형");
            $table->integer("point")->default(0)->comment("포인트");
            $table->string("ids")->nullable()->comment('아이디');
            $table->string("email")->nullable()->comment("이메일");
            $table->string("name")->nullable()->comment("이름");
            $table->string("contact")->nullable()->comment("연락처");
            $table->string("address")->nullable()->comment("주소");
            $table->string("address_detail")->nullable()->comment("주소 상세");
            $table->string("address_zipcode")->nullable()->comment("주소 우편번호");

            $table->string("business_number")->nullable()->comment("사업자번호");
            $table->string("company_title")->nullable()->comment("회사명");
            $table->string("company_president")->nullable()->comment("대표자명");
            $table->string("company_size")->nullable()->comment("기업형태");
            $table->string("company_type")->nullable()->comment("업종");
            $table->string("company_category")->nullable()->comment("업태");

            $table->boolean("agree_promotion_email")->default(false)->comment('이메일 홍보수신 동의여부');
            $table->boolean("agree_promotion_sms")->default(false)->comment('문자 홍보수신 동의여부');
            $table->boolean("agree_promotion_call")->default(false)->comment('전화 홍보수신 동의여부');

            $table->text('reason')->comment('탈퇴사유')->nullable();
            $table->text('and_so_on')->comment('기타 탈퇴사유')->nullable();

            $table->string('password')->nullable();

            $table->boolean("admin")->default(false);
            $table->boolean("master")->default(false);

            $table->text("push_token")->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
