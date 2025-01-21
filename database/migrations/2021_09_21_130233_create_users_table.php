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

            $table->text('delivery_requirement')->nullable()->comment('배송요청사항');
            $table->foreignId('grade_id')->nullable()->comment('등급')->constrained('grades');
            $table->string('code')->nullable()->comment('고유 코드');

            $table->text('social_id')->nullable();
            $table->string('social_platform')->nullable();

            $table->string("email")->nullable()->comment("이메일");
            $table->string("name")->nullable()->comment("이름");
            $table->string("contact")->nullable()->comment("연락처");

            $table->boolean("agree_promotion")->default(false)->comment('혜택수신동의');
            $table->string('code_recommend')->nullable()->comment('추천인코드');
            $table->integer("point")->default(0)->comment("포인트");

            $table->text('reason')->comment('탈퇴사유')->nullable();
            $table->text('and_so_on')->comment('기타 탈퇴사유')->nullable();
            $table->string('password')->nullable();

            $table->integer('count_family')->nullable()->comment('가구원수');
            $table->date('birth')->nullable()->comment('생년월일');

            $table->boolean('always_use_coupon_for_package')->default(1)->comment('꾸러미 결제 시 쿠폰 자동 적용여부');
            $table->boolean('always_use_point_for_package')->default(1)->comment('꾸러미 결제 시 적립금 자동사용여부');
            $table->text('message')->nullable()->comment('프로필 메시지');
            $table->string('nickname')->nullable()->comment('닉네임');

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
