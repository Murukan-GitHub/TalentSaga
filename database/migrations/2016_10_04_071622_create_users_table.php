<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('username', 45);
            $table->string('password');
            $table->string('name');
            $table->string('last_name');
            $table->date('birthdate');
            $table->string('email')->unique();
            $table->string('picture')->nullable();
            $table->string('role', 50);
            $table->string('phone_number', 15)->nullable();
            $table->datetime('registration_date');
            $table->datetime('last_visit');
            $table->string('status', 45)->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->integer('escrow_amount')->nullable();
            $table->string('fb_id', 255)->nullable()->comment('facebook id');
            $table->string('tw_id')->nullable()->comment('twitter id');
            $table->string('gp_id')->nullable()->comment('google plus id');
            $table->string('referral_code', 32)->nullable();
            $table->integer('referral_user_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('referral_user_id')
                ->references('id')->on('users')
                ->onDelete('set null');
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
            $table->dropForeign(['referral_user_id']);
        });
        Schema::drop('users');
    }
}
