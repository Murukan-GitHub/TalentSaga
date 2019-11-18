<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfileExpertisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profile_expertises', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_profile_id')->unsigned();
            $table->integer('talent_expertise_id')->unsigned();
            $table->timestamps();

            $table->foreign('user_profile_id')
                ->references('id')->on('user_profiles')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('talent_expertise_id')
                ->references('id')->on('talent_expertises')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_profile_expertises', function (Blueprint $table) {
            $table->dropForeign(['user_profile_id']);
            $table->dropForeign(['talent_expertise_id']);
        });
        Schema::drop('user_profile_expertises');
    }
}
