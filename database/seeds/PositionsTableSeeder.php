<?php declare( strict_types = 1 );

use App\Department;
use Illuminate\Database\Seeder;

/**
 * Class PositionsTableSeeder
 */
class PositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $departments = Department::all();
	    $departments->each(function($department){
		    $positonDepartment = collect();
	    	foreach (range(1,5) as $index)
		    {
			    $positonDepartment->push(factory(\App\Position::class)->make(['name' => 'position'.$index]));
		    }
	    	
		    /** @var Department $department */
		    $department->positions()->saveMany($positonDepartment);
	    });
    }
}
