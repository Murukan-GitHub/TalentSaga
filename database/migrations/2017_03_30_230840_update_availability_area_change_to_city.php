<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAvailabilityAreaChangeToCity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_availability_areas', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
            $table->dropColumn('country_id');

            $table->integer('city_id')->unsigned()->nullable()->after('user_id');
            $table->foreign('city_id')
                ->references('id')->on('cities')
                ->onUpdate('cascade')
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
        Schema::table('user_availability_areas', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
            $table->dropColumn('city_id');

            $table->integer('country_id')->unsigned()->nullable()->after('user_id');
            $table->foreign('country_id')
                ->references('id')->on('countries')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }
}
