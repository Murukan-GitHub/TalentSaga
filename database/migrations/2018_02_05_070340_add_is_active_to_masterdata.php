<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsActiveToMasterdata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('countries', function($table) {
            $table->boolean('is_active')->default(0)->nullable()->after('name');
        });
        Schema::table('cities', function($table) {
            $table->boolean('is_active')->default(0)->nullable()->after('name');
        });
        Schema::table('talent_categories', function($table) {
            $table->boolean('is_active')->default(0)->nullable()->after('cover_image');
        });
        Schema::table('currencies', function($table) {
            $table->boolean('is_active')->default(0)->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('countries', function($table) {
            $table->dropColumn('is_active');
        });
        Schema::table('cities', function($table) {
            $table->dropColumn('is_active');
        });
        Schema::table('talent_categories', function($table) {
            $table->dropColumn('is_active');
        });
        Schema::table('currencies', function($table) {
            $table->dropColumn('is_active');
        });
    }
}
