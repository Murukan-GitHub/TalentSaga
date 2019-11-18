<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCurrencyAndMetricToProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_profiles', function($table) {
            $table->integer('currency_id')->unsigned()->nullable()->after('talent_description');
            $table->string('pricing_metric')->nullable()->after('currency_id');

            $table->foreign('currency_id')
                  ->references('id')->on('currencies')
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
        Schema::table('user_profiles', function($table) {
            $table->dropForeign(['currency_id']);
            
            $table->dropColumn('currency_id');
            $table->dropColumn('pricing_metric');
        });
    }
}
