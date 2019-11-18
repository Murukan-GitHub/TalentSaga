<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Data Initiation
        $this->call(UsersTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(ContentTypesTableSeeder::class);
        $this->call(FaqCategoriesTableSeeder::class);
        $this->call(FaqsTableSeeder::class);
    }
}
