<?php declare( strict_types = 1 );

namespace App\Http\Middleware;

use Closure;

/**
 * Class Ajax
 *
 * @package App\Http\Middleware
 */
class Ajax
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
	    if(!$request->ajax()){
		    return redirect()->route('405');
	    }
    	
        return $next($request);
    }
}
