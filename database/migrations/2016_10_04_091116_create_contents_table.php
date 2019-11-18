<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type_id')->unsigned()->nullable();
            $table->integer('category_id')->unsigned()->nullable();
            $table->string('title');
            $table->string('slug');
            $table->string('highlight', 512);
            $table->string('image')->nullable();
            $table->text('content');
            $table->string('attachment_file')->nullable();
            $table->string('status', 50)->nullable();
            $table->timestamps();

            $table->foreign('type_id')
                  ->references('id')->on('content_types')
                  ->onDelete('restrict');

            $table->foreign('category_id')
                  ->references('id')->on('content_categories')
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
        Schema::table('contents', function (Blueprint $table) {
            $table->dropForeign(['type_id']);
            $table->dropForeign(['category_id']);
        });
        Schema::drop('contents');
    }
}
