<?php declare( strict_types = 1 );

use App\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

/**
 * Class SubordinateEmployeesTableSeeder
 */
class SubordinateEmployeesTableSeeder extends Seeder {
	
	private $strData;
	
	private $id = 1;
	
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run (): void
	{
		$this->strData = 'id,employee_id,subordinate_id'."\n";
		
		$departments = \App\Department::all()->load( 'employees' );
		
		$departments->each( function ($department) {
			
			$employees = $department->employees;
			
			$bossDepartment = $employees->shift();
			$subordinates = $employees->splice(0,10);
			$employees = $employees->shuffle();
			$this->distributionToBoss($bossDepartment,$subordinates);
			
			$this->distributionEmployees($employees,$subordinates);
		} );
		
		Storage::put( 'csv/subordinate.csv', $this->strData );
		
		$this->writeToDB();
	}
	
	/**
	 * @param \App\Employee                  $boss
	 * @param \Illuminate\Support\Collection $subordinates
	 */
	public function distributionToBoss (Employee $boss,Collection $subordinates): void
	{
		$subordinates->each(function ($subordinate) use($boss){
			$this->strData .= $this->id++.','. $boss->id . ',' . $subordinate->id . "\n";
		});
	}
	
	/**
	 * @param \Illuminate\Support\Collection $employees
	 * @param \Illuminate\Support\Collection $boss
	 *
	 * @throws \Exception
	 */
	public function distributionEmployees (Collection $employees, Collection $boss): void
	{
		$randomCountSubordinates = random_int(4,8);
		$subordinates = $employees->splice( 0, $randomCountSubordinates * $boss->count() );
		
		$chunkSubordinates = $subordinates->chunk( $randomCountSubordinates );
		
		$boss->each( function ($bos) use ($chunkSubordinates) {
			if($chunkSubordinates->isNotEmpty())
			$this->distributionToBoss( $bos, $chunkSubordinates->shift());
		} );
		
		if($employees->isNotEmpty())
			$this->distributionEmployees( $employees, $subordinates );
		
	}
	
	/**
	 *
	 */
	public function writeToDB (): void
	{
		
		$pdo = DB::connection()->getPdo();
		
		$pdo->exec( "LOAD DATA LOCAL INFILE '"
		            . storage_path( 'app/csv/subordinate.csv' )
		            . "' INTO TABLE subordinate_employees CHARACTER SET UTF8 FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 ROWS" );
	}
}
