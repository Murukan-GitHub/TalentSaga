<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('settings')->delete();

        $data = [
            [
                'key'   => 'address',
                'value' => 'Jl. Pejaten Barat II Nomor 3A Jakarta Selatan - DKI Jakarta'
            ],
            [
                'key'   => 'brandname',
                'value' => 'TalentSaga'
            ],
            [
                'key'   => 'facebook',
                'value' => 'https://www.facebook.com/samplepage'
            ],
            [
                'key'   => 'googleplus',
                'value' => 'https://plus.google.com/+samplepage'
            ],
            [
                'key'   => 'latitude',
                'value' => '-6.27636'
            ],
            [
                'key'   => 'legalname',
                'value' => 'TALENTSAGA'
            ],
            [
                'key'   => 'longitude',
                'value' => '106.82429'
            ],
            [
                'key'   => 'phone',
                'value' => '021-123456789'
            ],
            [
                'key'   => 'twitter',
                'value' => 'https://twitter.com/samplepage'
            ]
        ];

        \DB::table('settings')->insert($data);
    }
}
