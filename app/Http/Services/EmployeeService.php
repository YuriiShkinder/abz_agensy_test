<?php declare( strict_types = 1 );

namespace App\Http\Services;

use App\Employee;
use App\Http\Repositories\EmployeeRepository;
use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\EditEmployeeRequest;
use App\Http\Requests\RewriteBossEmployeeRequest;
use App\Position;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class EmployeeService
 *
 * @package App\Http\Services
 */
class EmployeeService {
	
	private $repository;
	private $positionModal;
	private $imageService;
	
	/**
	 * DepartmentService constructor.
	 *
	 * @param \App\Http\Repositories\EmployeeRepository $repository
	 * @param \App\Position                             $position
	 * @param \App\Http\Services\LoadImageService       $imageService
	 */
	public function __construct (EmployeeRepository $repository, Position $position, LoadImageService $imageService)
	{
		$this->repository    = $repository;
		$this->positionModal = $position;
		$this->imageService  = $imageService;
	}
	
	/**
	 * @param \App\Employee $employee
	 *
	 * @return \App\Employee[]|\Illuminate\Database\Eloquent\Collection
	 */
	public function getEmployeeSubordinates (Employee $employee) : Collection
	{
		return $employee->subordinate->load(['position','subordinate']);
	}
	
	/**
	 * @param \App\Http\Requests\RewriteBossEmployeeRequest $request
	 *
	 * @return bool
	 */
	public function rewriteBossEmployee (RewriteBossEmployeeRequest $request): bool
	{
		/** @var Employee $newBoss */
		$newBoss  = $this->repository->onlyHash($request->get('newBoss'));
		
		/** @var Employee $employee */
		$employee = $this->repository->onlyHash($request->get('employee'));
		
		$employee->boss->first()->subordinate()->detach($employee);
		
		$newBoss->subordinate()->attach($employee);
		
		return true;
	}
	
	/**
	 * @param \Illuminate\Http\Request $request
	 *
	 * @param int                      $countPage
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function searchEmployees (Request $request, $countPage = 20) : LengthAwarePaginator
	{
		$employees = $this->repository->getByFieldsLike($request->get('field'), $request->get('value'));
		
		$employees = $employees->with('position')->paginate($countPage);
	
		return $employees;
	}
	
	/**
	 * @param \App\Http\Requests\CreateEmployeeRequest $request
	 *
	 * @return \Illuminate\Database\Eloquent\Model
	 * @throws \App\Exceptions\ErrorUploadImageException
	 */
	public function createEmployee (CreateEmployeeRequest $request): Model
	{
		$data     = $this->getDataRequest($request);
		
		$employee = $this->repository->create($data);
		
		/** @var Employee $boss */
		$boss     = $this->repository->onlyHash($request->get('boss'));
		
		$boss->subordinate()->attach($employee);
		
		return $employee;
	}
	
	/**
	 * @param \App\Http\Requests\EditEmployeeRequest $request
	 * @param \App\Employee                          $employee
	 *
	 * @return bool
	 * @throws \App\Exceptions\ErrorUploadImageException
	 */
	public function editEmployee (EditEmployeeRequest $request, Employee $employee) : bool
	{
		$data = $this->getDataRequest($request);
		
		$hashBoss = $request->get('boss');
		/** @var Employee $oldBoss */
		$oldBoss = $employee->boss->first();
		
		if( $hashBoss !=  $oldBoss->hash)
		{
			$employee->boss()->detach();
			
			/** @var Employee $boss */
			$boss     = $this->repository->onlyHash($request->get('boss'));
			
			$boss->subordinate()->attach($employee);
		}
		
		return $employee->update($data);
	}
	
	/**
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return array
	 * @throws \App\Exceptions\ErrorUploadImageException
	 */
	private function getDataRequest(Request $request): array
	{
		$data     = $request->only(['first_name', 'last_name', 'data_reception', 'salary']);
		
		if($request->hasFile('img'))
			$data['img'] = $request->has('old') ? $this->imageService->reload($request) : $this->imageService->upload($request);
			
		$data['position_id'] = $this->positionModal->whereHash($request->get('position'))->first()->id;
		
		return $data;
	}
	
	/**
	 * @param \App\Employee $employee
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function removeEmployee (Employee $employee): bool
	{
		$result = $this->rewriteToBossSubordinate($employee) ? $employee->delete() : false;
		
		!$result ?: $this->imageService->removeEmployeeImage($employee->img);
		
		return  $result;
	}
	
	/**
	 * @param \App\Employee $employee
	 *
	 * @return bool
	 */
	private function rewriteToBossSubordinate (Employee $employee): bool
	{
		$employee = $employee->load(['subordinate', 'boss']);
		
		if($this->checkBoss($employee) )
		{
			if($employee->subordinate->isNotEmpty()){
				/** @var Employee $bossSubordinate */
				$bossSubordinate = $employee->boss->first();
				
				$removedEmployeeSubordinates = $employee->subordinate;
				
				$bossSubordinate->subordinate()->attach( $removedEmployeeSubordinates );
			}
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * @param \App\Employee $employee
	 *
	 * @return bool
	 */
	private function checkBoss (Employee $employee): bool
	{
		return $employee->boss->isNotEmpty();
	}
	
	
}