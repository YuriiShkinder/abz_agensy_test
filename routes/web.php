<?php declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'cors'], function (){
	
	Route::get('/',['as' => 'showDepartments', 'uses' => 'DepartmentController@showDepartments']);
	
	Route::group(['middleware' => 'ajax'], function (){
		
		Route::get('department-employees/{department}',['as' => 'departmentEmployees', 'uses' => 'DepartmentController@showDepartmentBossAndSubordinate']);
		
		Route::get('employee-subordinates/{employee}',['as' => 'employeeSubordinates', 'uses' => 'EmployeeController@showEmployeeSubordinates']);
		
		Route::post('rewrite-boss-employee',['as' => 'rewriteBossEmployee', 'uses' => 'EmployeeController@rewriteBossEmployee']);
		
	});
	
	Route::post('/login', ['as' => 'login', 'uses'=>'Auth\LoginController@login']);
	
	Route::get('/logout', ['as' => 'logout', 'uses'=>'Auth\LoginController@logout']);

	Route::group(['middleware' => ['auth']], function (){
		
		Route::group(['middleware' => 'ajax'], function (){
			
			Route::get('orderBy-employees/{department}', ['as' => 'orderByEmployees', 'uses' => 'CrudEmployeesController@showOrderByEmployees']);
			
			Route::get('search-employees', ['as' => 'searchEmployees', 'uses' => 'CrudEmployeesController@searchEmployees']);
			
			Route::match(['get', 'post'],'create-employee/{department}', ['as' => 'createEmployee', 'uses' => 'CrudEmployeesController@createEmployee']);
			
			Route::group(['prefix' => 'create-employee/{department}'],function (){
				
				Route::get('/', ['as' => 'showEmployeeModalForm', 'uses' => 'CrudEmployeesController@showEmployeeModalForm']);
				
				Route::post('/', ['as' => 'createEmployee', 'uses' => 'CrudEmployeesController@createEmployee']);
				
			});
			
			Route::group(['prefix' => 'edit-employee/{employee}'],function (){
				
				Route::get('/', ['as' => 'showEditEmployeeModalForm', 'uses' => 'CrudEmployeesController@showEditEmployeeModalForm']);
				
				Route::post('/', ['as' => 'editEmployee', 'uses' => 'CrudEmployeesController@editEmployee']);
				
			});
			
			Route::get('search-boss/{department}', ['as' => 'searchBoss', 'uses' => 'CrudEmployeesController@searchBoss']);
			
			Route::delete('remove-employee/{employee}', ['as' => 'removeEmployee', 'uses' => 'CrudEmployeesController@removeEmployee']);
			
		});
		
		Route::get('employees-department/{department}', ['as' => 'employeesDepartment', 'uses' => 'CrudEmployeesController@showEmployees']);
		
		});
});

Route::get('404', ['as' => '404', 'uses' => 'ErrorController@notFound']);

Route::get('500', ['as' => '500', 'uses' => 'ErrorController@fatal']);

Route::get('405', ['as' => '405', 'uses' => 'ErrorController@notAjax']);