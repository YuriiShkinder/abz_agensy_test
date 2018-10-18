<?php declare( strict_types = 1 );

use App\Department;
use Faker\Generator as Faker;

/** @var Faker $factory */
$factory->define( Department::class, function (Faker $faker) {
	return [
        'name' => ucfirst($faker->bs)
    ];
});
