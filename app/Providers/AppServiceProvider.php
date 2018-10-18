<?php declare( strict_types = 1 );

namespace App\Providers;

use App\Department;
use App\Http\Repositories\DepartmentRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider
 *
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
	    \DB::listen(function ($query) {
//		     dump($query->sql);
//		     $query->bindings;
//		     $query->time;
	    });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }
}
