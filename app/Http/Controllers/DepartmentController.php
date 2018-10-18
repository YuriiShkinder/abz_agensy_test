<?php declare( strict_types = 1 );

namespace App\Http\Controllers;

use App\Department;
use App\Http\Services\DepartmentService;
use Illuminate\View\View;

/**
 * Class DepartmentController
 *
 * @package App\Http\Controllers
 */
class DepartmentController extends Controller
{
	
	/**
	 * DepartmentController constructor.
	 *
	 * @param \App\Http\Services\DepartmentService $departmentService
	 */
	public function __construct (DepartmentService $departmentService)
	{
		$this->service = $departmentService;
	}
	
	/**
	 * @return \Illuminate\View\View
	 */
	public function showDepartments (): View
	{
	    $departments = $this->service->getDepartments();

	    return  view('departments')->with(compact('departments'));
    }
	
	/**
	 * @param \App\Department $department
	 *
	 * @return \Illuminate\View\View
	 */
	public function showDepartmentBossAndSubordinate (Department $department) : View
    {
	    $employees = $this->service->getDepartmentBossAndSubordinate($department);
	
    	return view('departmentEmployees')->with(compact('employees'));
    }
    
 
}
