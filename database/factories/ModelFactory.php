<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\UserEducation::class, function (Faker\Generator $faker) {
	return [
        'degree_id' => '',
        'major_id' => '',
        'college' => '',
        'year_in' => '',
        'year_out' => '',
        'gpa' => '',
        'gpa_max_range' => ''
    ];
});

$factory->define(App\Models\UserExperience::class, function (Faker\Generator $faker) {
	return [
        'user_id' => '',
        'company' => '',
        'industry_id' => '',
        'profession_id' => '',
        'job_level_id' => '',
        'responsibilities' => '',
        'start_date' => '',
        'end_date' => '',
        'monthly_salary' => '',
        'is_current_job' => '',
        'resign_reason' => '',
    ];
});

$factory->define(App\Models\UserSkill::class, function (Faker\Generator $faker) {
	return [
        'user_id' => '',
        'skill_id' => '',
        'name' => '',
        'level' => ''
    ];
});

$factory->define(App\Models\UserLanguageCapability::class, function (Faker\Generator $faker) {
	return [
        'user_id' => '',
        'language_id' => '',
        'listen_capability' => '',
        'speak_capability' => '',
        'read_capability' => '',
        'write_capability' => ''
    ];
});
