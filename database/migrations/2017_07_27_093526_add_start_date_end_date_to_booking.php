<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStartDateEndDateToBooking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_bookings', function($t) {
            $t->renameColumn('event_date', 'event_date_start');
        });
        Schema::table('user_bookings', function(Blueprint $table) {
            $table->date('event_date_end')->nullable()->after('event_date_start');
            $table->string('email')->nullable()->after('status');
            $table->string('phone_number')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_bookings', function($t) {
            $t->renameColumn('event_date_start', 'event_date');
        });
        Schema::table('user_bookings', function($table) {
            $table->dropColumn('event_date_end');
            $table->dropColumn('email');
            $table->dropColumn('phone_number');
        });
    }
}
