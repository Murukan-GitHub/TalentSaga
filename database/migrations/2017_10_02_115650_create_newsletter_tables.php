<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNewsletterTables extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newsletters', function(Blueprint $table) {
            $table->increments('id');
            $table->string('email_sender_name', 100)->nullable();
            $table->string('email_subject', 100)->nullable();
            $table->string('banner_top_image', 45)->nullable();
            $table->string('banner_top_url', 255)->nullable();
            $table->string('banner_top_title', 100)->nullable();
            $table->text('email_body')->nullable();
            $table->string('banner_bottom_image', 45)->nullable();
            $table->string('banner_bottom_url', 255)->nullable();
            $table->string('banner_bottom_title', 100)->nullable();
            $table->string('recipient', 45)->nullable();
            $table->string('status')->default('created');
            $table->datetime('limited_start_date')->nullable();
            $table->datetime('limited_end_date')->nullable();
            $table->integer('limited_nb_sent')->nullable();
            $table->integer('actual_nb_recepient')->nullable();
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
        Schema::drop('newsletters');
    }

}
