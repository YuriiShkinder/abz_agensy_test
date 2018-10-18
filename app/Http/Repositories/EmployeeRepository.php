<?php declare( strict_types = 1 );

namespace App\Http\Repositories;

use App\Employee;


/**
 * Class EmployeeRepository
 *
 * @package App\Http\Repositories
 */
class EmployeeRepository extends BaseRepository {
	
	/**
	 * DepartmentRepository constructor.
	 *
	 * @param \App\Employee $employee
	 */
	public function __construct (Employee $employee)
	{
		$this->model = $employee;
	}
	
	
}