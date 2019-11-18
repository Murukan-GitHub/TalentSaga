<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('users')->delete();

        \DB::table('users')->insert(array(
            0 => array(
                'id'                => 1,
                'username'          => 'admin',
                'password'          => Hash::make('rahasia'),
                'name'              => 'Admin',
                'last_name'         => 'Talentsaga',
                'birthdate'         => '1970-01-01',
                'email'             => 'admin@talentsaga.com',
                'picture'           => null,
                'role'              => 'admin',
                'phone_number'      => null,
                'registration_date' => '2012-01-01 00:00:00',
                'last_visit'        => '2016-11-24 07:35:19',
                'status'            => 'active',
                'remember_token'    => '6WVa62TflQSVdcth3ERjfArifeSnPDcPz0qTWneeju38NKWYmTAobrINbLbv',
                'fb_id'             => null,
                'tw_id'             => null,
                'gp_id'             => null,
                'referral_code'     => null,
                'referral_user_id'  => null,
                'created_at'        => '2014-09-22 03:30:25',
                'updated_at'        => '2016-11-24 07:35:38',
            )
        ));
    }
}
