<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('type_id')->unsigned();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();

            $table->foreign('parent_id')
                  ->references('id')->on('content_categories')
                  ->onDelete('set null');

            $table->foreign('type_id')
                  ->references('id')->on('content_types')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('content_categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropForeign(['type_id']);
        });
        Schema::drop('content_categories');
    }
}
