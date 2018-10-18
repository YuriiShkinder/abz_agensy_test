<?php declare( strict_types = 1 );

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

/**
 * Class Handler
 *
 * @package App\Exceptions
 */
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];
	
	/**
	 * Report or log an exception.
	 *
	 * @param  \Exception $exception
	 *
	 * @return void
	 * @throws \Exception
	 */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
	    if ($exception instanceof AuthenticationException)
		    return $this->unauthenticated($request, $exception);

    	if($this->isHttpException($exception))
	    {
	    	switch ($exception->getCode())
		    {
			    case 404 :
			    	return redirect()->route('404');
			    	break;
			    case 500;
			        return redirect()->route('500');
			        break;
		    }
	    }

        if($exception instanceof ValidationException)
        	return response()->json(['success' => false,'errors' => $exception->errors()]);

        return parent::render($request, $exception);
    }
	
	
	/**
	 * @param \Illuminate\Http\Request                 $request
	 * @param \Illuminate\Auth\AuthenticationException $exception
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
	 */
	public function unauthenticated ($request, AuthenticationException $exception)
    {
	    return redirect()->route('showDepartments');
    }
	
}