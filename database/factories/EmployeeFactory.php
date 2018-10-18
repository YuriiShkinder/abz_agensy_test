<?php declare( strict_types = 1 );

use App\Employee;
use Faker\Generator as Faker;

/** @var Faker $factory */
$factory->define( Employee::class, function (Faker $faker) {
    return [
        'first_name' => $faker->name,
        'last_name' => $faker->lastName,
        'img'       => $faker->imageUrl(),
    ];
});
