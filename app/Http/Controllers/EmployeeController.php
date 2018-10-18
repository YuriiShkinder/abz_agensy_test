<?php declare( strict_types = 1 );

namespace App\Http\Controllers;

use App\Employee;
use App\Http\Requests\RewriteBossEmployeeRequest;
use App\Http\Services\EmployeeService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;


/**
 * Class EmployeeController
 *
 * @package App\Http\Controllers
 */
class EmployeeController extends Controller
{
	protected $departmentService;
	
	/**
	 * EmployeeController constructor.
	 *
	 * @param \App\Http\Services\EmployeeService $employeeService
	 */
	public function __construct (EmployeeService $employeeService)
    {
    	$this->service = $employeeService;
    }
	
	/**
	 * @param \App\Employee $employee
	 *
	 * @return \Illuminate\View\View
	 */
	public function showEmployeeSubordinates (Employee $employee): View
	{
		/** @var \App\Employee $employee */
		$employees = $this->service->getEmployeeSubordinates($employee);
		
		return view('employeeSubordinates')->with(compact('employees'));
	}
	
	/**
	 * @param \App\Http\Requests\RewriteBossEmployeeRequest $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function rewriteBossEmployee (RewriteBossEmployeeRequest $request): JsonResponse
	{
		/** @var bool $result */
		$result = $this->service->rewriteBossEmployee($request);
		
		return response()->json(['success' => $result]);
	}
	
}
