<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_galleries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('type', 16);
            $table->string('title', 128)->nullable();
            $table->string('cover_url', 512)->nullable();
            $table->string('image_media_url', 512);
            $table->string('external_media_url', 512)->nullable();
            $table->string('status', 32);
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
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
        Schema::table('user_galleries', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::drop('user_galleries');
    }
}
