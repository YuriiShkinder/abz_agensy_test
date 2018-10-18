<?php declare( strict_types = 1 );

use App\Department;
use Illuminate\Database\Seeder;

/**
 * Class DepartmentsTableSeeder
 */
class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
    	foreach (range(1,20) as $index) {
		    factory( Department::class )->create( [
			    'name' => 'department' . $index
		    ] );
	    }
    }
}
