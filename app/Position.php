<?php declare( strict_types = 1 );

namespace App;

use App\Http\Traits\HashTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Position
 *
 * @package App
 * @property-read \App\Department $department
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Employee[] $employees
 * @mixin \Eloquent
 */
class Position extends Model
{
	use HashTrait;
	
	protected $hidden = ['id', 'department_id'];
	
	protected $fillable = [
		'name',
		'hash',
		'department_id',
	];
	
	/**
	 * @return HasMany
	 */
	public function employees (): HasMany
	{
		return $this->hasMany(Employee::class);
	}
	
	/**
	 * @return BelongsTo
	 */
	public function department (): BelongsTo
	{
		return $this->belongsTo(Department::class);
	}
}
