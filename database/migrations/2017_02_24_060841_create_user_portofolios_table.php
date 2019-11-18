<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPortofoliosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_portofolios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->date('event_date');
            $table->string('event_name', 128);
            $table->string('description', 4096);
            $table->string('url', 512)->nullable();
            $table->string('youtube_url', 512)->nullable();
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
        Schema::table('user_portofolios', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::drop('user_portofolios');
    }
}
