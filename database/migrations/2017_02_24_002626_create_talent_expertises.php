<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTalentExpertises extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('talent_expertises', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('talent_category_id')->unsigned();
            $table->string('slug', 128);
            $table->string('name', 128);
            $table->boolean('is_curated')->default(0);
            $table->timestamps();

            $table->foreign('talent_category_id')
                ->references('id')->on('talent_categories')
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
        Schema::table('talent_expertises', function (Blueprint $table) {
            $table->dropForeign(['talent_category_id']);
        });
        Schema::drop('talent_expertises');
    }
}
