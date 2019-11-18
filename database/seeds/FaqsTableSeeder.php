<?php

use Illuminate\Database\Seeder;

class FaqsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('faqs')->delete();

        \DB::table('faqs')->insert(array(
            0 => array(
                'id'              => 1,
                'faq_category_id' => 1,
                'question'        => 'What is Talentsaga?',
                'answer'          => 'We are talent booking services.',
                'created_at'      => '2016-11-24 08:43:06',
                'updated_at'      => '2016-11-24 08:43:06',
            ),
        ));
    }
}
