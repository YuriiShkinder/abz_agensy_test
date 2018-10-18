<?php declare( strict_types = 1 );

namespace App\Http\Services;

use App\Department;
use App\Employee;
use App\Http\Repositories\DepartmentRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


/**
 * Class DepartmentService
 *
 * @package App\Http\Services
 */
class DepartmentService {
	
	private $repository;
	
	/**
	 * DepartmentService constructor.
	 *
	 * @param \App\Http\Repositories\DepartmentRepository $repository
	 */
	public function __construct (DepartmentRepository $repository)
	{
		$this->repository = $repository;
	}
	
	/**
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function getDepartments () : Collection
	{
		return $this->repository->allWithCountEmployees();
	}
	
	/**
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function getAllDepartments () : Collection
	{
		return $this->repository->all();
	}
	
	/**
	 * @param \App\Department $department
	 *
	 * @return \Illuminate\Database\Eloquent\Model|mixed|null|object|static
	 */
	public function getDepartmentBossAndSubordinate (Department $department) : ?Employee
	{
		$departmentBoss = $department->employees()
	                                 ->with(['subordinate.position','position'])
	                                 ->whereNotExists(function ($query){
		                                 $query->select(\DB::raw(1))
		                                       ->from('subordinate_employees')
		                                       ->whereRaw('subordinate_employees.subordinate_id = employees.id');})
	                                 ->first();
		return $departmentBoss;
	}
	
	/**
	 * @param \App\Department $department
	 *
	 * @param int             $countPage
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getEmployeesPaginate (Department $department, $countPage = 20): LengthAwarePaginator
	{
		$departmentEmployees = $department->employees()
										  ->with(['position','boss'])
			                              ->paginate($countPage);
		
		return $departmentEmployees;
	}
	
	/**
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function getFirstDepartment (): Model
	{
		return $this->repository->getFirstModel();
	}
	
	/**
	 * @param \Illuminate\Http\Request $request
	 * @param \App\Department          $department
	 * @param int                      $countPage
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getOrderByEmployees (Request $request, Department $department, $countPage = 50): LengthAwarePaginator
	{
		$departmentEmployees = $department->employees()
		                                  ->with(['position','boss'])
										  ->orderBy($request->get('field'), $request->get('orderBy'))
		                                  ->paginate($countPage);
		
		return $departmentEmployees;
	}
	
	/**
	 * @param \App\Department $department
	 *
	 * @return \App\Position[]|\Illuminate\Database\Eloquent\Collection
	 */
	public function getPositionsDepartment (Department $department) : Collection
	{
		return $department->positions;
	}
	
	/**
	 * @param \Illuminate\Http\Request $request
	 * @param \App\Department          $department
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function searchBoss (Request $request, Department $department) : Collection
	{
		$emplyees = $department->employees()->where('last_name', 'like', $request->get('value').'%')->get();
	
		return $emplyees;
	}
	
	/**
	 * @param \App\Employee $employee
	 *
	 * @return \App\Department
	 */
	public function getDepartmentFromEmployee(Employee $employee): Department
	{
		return $employee->load('position.department')->position->department;
	}
}