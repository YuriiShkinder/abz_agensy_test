<?php declare( strict_types = 1 );

use App\Position;
use Faker\Generator as Faker;

/** @var Faker $factory */
$factory->define( Position::class, function (Faker $faker) {
	return [
		'name' => $faker->jobTitle,
	];
} );
