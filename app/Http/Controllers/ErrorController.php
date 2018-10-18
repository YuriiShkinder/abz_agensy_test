<?php declare( strict_types = 1 );

namespace App\Http\Controllers;


/**
 * Class ErrorController
 *
 * @package App\Http\Controllers
 */
class ErrorController extends Controller
{
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function notFound()
	{
		return view('errors.404');
	}
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function notAjax()
	{
		return view('errors.405');
	}
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function fatal()
	{
		return view('errors.500');
	}
}
