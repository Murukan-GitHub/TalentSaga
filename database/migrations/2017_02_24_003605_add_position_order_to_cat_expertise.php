<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPositionOrderToCatExpertise extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('talent_categories', function(Blueprint $table) {
            $table->integer('position_order')->unsigned()->nullable()->after('id');
        });

        Schema::table('talent_expertises', function(Blueprint $table) {
            $table->integer('position_order')->unsigned()->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('talent_categories', function($table) {
            $table->dropColumn('position_order');
        });
        Schema::table('talent_expertises', function($table) {
            $table->dropColumn('position_order');
        });
    }
}
