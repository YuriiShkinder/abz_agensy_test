<?php declare( strict_types = 1 );

use Faker\Generator as Faker;

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

/** @var Faker $factory */
$factory->define( App\User::class, function (Faker $faker) {
	return [
		'login'       => 'abz',
		'password'    => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
		'employee_id' => 1,
	];
} );