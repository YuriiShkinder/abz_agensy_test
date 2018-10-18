<?php declare( strict_types = 1 );

namespace App\Http\Repositories;

use App\Department;
use Illuminate\Database\Eloquent\Collection;


/**
 * Class DepartmentRepository
 *
 * @package App\Http\Repositories
 */
class DepartmentRepository extends BaseRepository {
	
	
	/**
	 * DepartmentRepository constructor.
	 *
	 * @param \App\Department $department
	 */
	public function __construct (Department $department)
	{
		$this->model = $department;
	}
	
	
	/**
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function allWithCountEmployees () : Collection
	{
		return $this->model->withCount('employees')->get();
	}

}