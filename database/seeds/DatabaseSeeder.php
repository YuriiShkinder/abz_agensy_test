<?php declare( strict_types = 1 );

use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 */
class DatabaseSeeder extends Seeder {
	
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run (): void
	{
		$this->call( DepartmentsTableSeeder::class );
		$this->call( PositionsTableSeeder::class );
		$this->call( EmployeesTableSeeder::class );
		$this->call( UsersTableSeeder::class );
		$this->call(SubordinateEmployeesTableSeeder::class);
	}
}
