<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTalentCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('talent_categories', function(Blueprint $table) {
            $table->foreign('parent_id')
                  ->references('id')->on('talent_categories')
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
        Schema::table('talent_categories', function (Blueprint $table) {
            $table->dropForeign('talent_categories_parent_id_foreign');
        });
    }
}
