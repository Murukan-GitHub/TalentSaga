<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sender_name', 100)->nullable();
            $table->string('sender_email', 255);
            $table->string('category', 48);
            $table->string('content', 1024);
            $table->string('reply', 1024)->nullable();
            $table->string('status', 50)->nullable();
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
        Schema::drop('contact_messages');
    }
}
