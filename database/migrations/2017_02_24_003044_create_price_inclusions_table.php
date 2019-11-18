<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceInclusionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_inclusions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('position_order')->nullable();
            $table->string('name', 128);
            $table->boolean('is_curated')->default(0);
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
        Schema::drop('price_inclusions');
    }
}
