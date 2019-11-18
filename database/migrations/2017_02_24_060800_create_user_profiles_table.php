<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('talent_category_id')->unsigned();
            $table->string('talent_profession', 128);
            $table->integer('talent_expertise_id')->unsigned();
            $table->mediumText('talent_description')->nullable();
            $table->integer('price_estimation')->unsigned()->nullable();
            $table->string('price_notes', 2048);
            $table->boolean('contact_for_price')->default(0);
            $table->integer('city_id')->unsigned()->nullable();
            $table->integer('country_id')->unsigned()->nullable();
            $table->string('street_name', 255)->nullable();
            $table->string('street_number', 8)->nullable();
            $table->string('zip_code', 10)->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->double('height', 10, 2)->nullable();
            $table->double('weight', 10, 2)->nullable();
            $table->string('facebook_page', 512)->nullable();
            $table->string('twitter_page', 512)->nullable();
            $table->string('instagram_page', 512)->nullable();
            $table->string('youtube_page', 512)->nullable();
            $table->string('status', 32);
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
            $table->foreign('talent_category_id')
                  ->references('id')->on('talent_categories')
                  ->onDelete('cascade');
            $table->foreign('talent_expertise_id')
                  ->references('id')->on('talent_expertises')
                  ->onDelete('cascade');
            $table->foreign('city_id')
                  ->references('id')->on('cities')
                  ->onDelete('set null');
            $table->foreign('country_id')
                  ->references('id')->on('countries')
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
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['talent_category_id']);
            $table->dropForeign(['talent_expertise_id']);
            $table->dropForeign(['city_id']);
            $table->dropForeign(['country_id']);
        });
        Schema::drop('user_profiles');
    }
}
