<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateImageMediaUrlInGalleryNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_galleries', function (Blueprint $table) {
            $table->string('image_media_url', 512)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_galleries', function (Blueprint $table) {
            $table->string('image_media_url', 512)->change();
        });
    }
}
