<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSocialColumnsOnUsersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('gp_access_token')->nullable()->after('gp_id');
            $table->string('gp_secret')->nullable()->after('gp_access_token');
            $table->string('li_id')->nullable()->after('gp_secret');
            $table->string('li_access_token')->nullable()->after('li_id');
            $table->string('li_secret')->nullable()->after('li_access_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('gp_access_token', 'gp_secret', 'li_id', 'li_access_token', 'li_secret');
        });
    }
}
