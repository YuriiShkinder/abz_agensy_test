<?php
declare( strict_types = 1 );

namespace App;

use App\Http\Traits\SlugTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Class Department
 *
 * @package App
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Employee[] $employees
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Position[] $positions
 * @mixin \Eloquent
 */
class Department extends Model {
	
	use SlugTrait;
	
	protected $hidden = ['id'];
	
	protected $fillable = [
		'name',
		'slug'
	];
	
	/**
	 * @return HasMany
	 */
	public function positions (): HasMany
	{
		return $this->hasMany( Position::class );
	}
	
	/**
	 * @return HasManyThrough
	 */
	public function employees (): HasManyThrough
	{
		return $this->hasManyThrough(Employee::class,Position::class);
	}
}
