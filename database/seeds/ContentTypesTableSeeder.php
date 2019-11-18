<?php

use Illuminate\Database\Seeder;

class ContentTypesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('content_types')->delete();

        \DB::table('content_types')->insert(array(
            0 => array(
                'id'         => 1,
                'name'       => 'Static',
                'code'       => 'static',
                'created_at' => '2016-11-24 08:39:59',
                'updated_at' => '2016-11-24 08:39:59',
            ),
        ));
    }
}
