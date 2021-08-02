<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function () {
    $name = $this->faker->name;
    $ramdon = Str::random(10);
    $last_name = $this->faker->lastName;
    return [
        'name' => $name,
        "last_name" => $last_name,
        "identification" => $this->faker->numberBetween(),
        'address' => $this->faker->address,
//        'biography' => $this->faker->text,
        "identification_type_id" => App\Models\IdentificationType::all()->random()->id,
        'email' => $this->faker->unique()->safeEmail,
//        'birthday' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
        "phone" => $this->faker->numberBetween(),
        'email_verified_at' => now(),
        // 'country_id' => \App\Models\Country::all()->random()->id,
        // 'city_id' => \App\Models\City::all()->random()->id,
        'slug' => Str::slug($name . '-' . $last_name . '-' . $ramdon, '-'),
        "picture" => '/images/user-profile.png',
//        "web_site" => $this->faker->url,
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});
