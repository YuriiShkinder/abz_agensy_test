<?php declare( strict_types = 1 );

use App\Employee;
use App\Position;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Faker\Factory;

/**
 * Class EmployeesTableSeeder
 */
class EmployeesTableSeeder extends Seeder {
	
	private $strData;
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function run (): void
	{
		\factory( Employee::class)->create();
		/** @var Faker\Generator faker */
		$faker = Factory::create( 'ru_RU' );
		
		$countPositions = Position::all()->count();
		$this->strData = 'id,hash,first_name,last_name,img,position_id,data_reception,salary' . "\n";
		for ($i = 2; $i <= 50000; $i ++) {
			
			$dataReception = Carbon::now()->subYear(random_int( 1, 5 ))
									      ->subMonth(random_int( 1, 12 ))
									      ->subDay(random_int( 1, 30 ))
									      ->toDateString();
			
			$this->strData .= $i . ',"' . Uuid::uuid4()->toString() . '","'
			         . $faker->firstName . '","' . $faker->lastName. '","'
			         . $faker->imageUrl(220,160).'","' . random_int( 1, $countPositions ) . '","'
			         . $dataReception . '","' . random_int( 300, 1500 ) . '"'
			         . "\n";
			
		}
		Storage::put( 'csv/employees.csv', $this->strData );
		
		$this->writeToDB();
	}
	
	/**
	 *
	 */
	public function writeToDB (): void
	{
		$pdo = DB::connection()->getPdo();
		
		$pdo->exec( "LOAD DATA LOCAL INFILE '"
		            . storage_path( 'app/csv/employees.csv' )
		            . "' INTO TABLE employees CHARACTER SET UTF8 FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 ROWS" );
		
	}
}
