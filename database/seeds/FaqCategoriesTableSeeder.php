<?php

use Illuminate\Database\Seeder;

class FaqCategoriesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('faq_categories')->delete();

        \DB::table('faq_categories')->insert(array(
            0 => array(
                'id'             => 1,
                'position_order' => 1,
                'name'           => 'General FAQ',
                'slug'           => 'general-faq',
                'created_at'     => '2016-11-24 08:42:19',
                'updated_at'     => '2016-11-24 08:42:19',
            ),
        ));
    }
}
