<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBannerImageToTalentCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('talent_categories', function(Blueprint $table) {
            $table->string('banner_image', 255)->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('talent_categories', function(Blueprint $table) {
            $table->dropColumn('banner_image', 255);
        });
    }
}
