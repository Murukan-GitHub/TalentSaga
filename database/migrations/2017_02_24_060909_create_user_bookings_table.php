<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('talent_user_id')->unsigned();
            $table->string('event_title', 128)->nullable();
            $table->string('event_detail', 4096);
            $table->date('event_date');
            $table->time('event_start_time')->nullable();
            $table->time('event_end_time')->nullable();
            $table->string('location', 255);
            $table->string('status', 32);
            $table->integer('talent_rate')->unsigned()->nullable();
            $table->datetime('talent_review_date')->nullable();
            $table->string('talent_review', 2048)->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('talent_user_id')
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
        Schema::table('user_bookings', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['talent_user_id']);
        });
        Schema::drop('user_bookings');
    }
}
