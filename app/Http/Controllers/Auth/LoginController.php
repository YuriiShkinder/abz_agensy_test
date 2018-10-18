<?php declare( strict_types = 1 );

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class LoginController
 *
 * @package App\Http\Controllers\Auth
 */
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
	
	/**
	 * @return string
	 */
	public function username(): string
	{
		return 'login';
	}
	
	
	/**
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
	 */
	public function login(Request $request)
	{
		$this->validateLogin($request);
		
		if($this->attemptLogin($request)) {
			$request->session()->regenerate();
			
			$this->clearLoginAttempts($request);
			
			return \response(['success' => true]);
		}
		
		return \response(['success' => false, 'error'=> 'Auth failed!' ]);;
	}
	
}
