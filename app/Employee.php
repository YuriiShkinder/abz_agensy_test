<?php declare( strict_types = 1 );

namespace App;

use App\Http\Traits\HashTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Employee
 *
 * @property integer                                                       id
 * @package App
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Employee[] $boss
 * @property-read string                                                   hash
 * @property-read \App\Position                                            $position
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Employee[] $subordinate
 * @property-read \App\User                                                $user
 * @mixin \Eloquent
 */
class Employee extends Model {
	
	use HashTrait;
	
	protected $hidden = ['id','position_id'];
	
	protected $fillable = [
		'id',
		'first_name',
		'last_name',
		'position_id',
		'hash',
		'data_reception',
		'salary',
		'img'
	];
	
	/**
	 * @return BelongsTo
	 */
	public function position (): BelongsTo
	{
		return $this->belongsTo(Position::class);
	}
	
	
	/**
	 * @return BelongsToMany
	 */
	public function subordinate (): BelongsToMany
	{
		return $this->belongsToMany(self::class, 'subordinate_employees', 'employee_id', 'subordinate_id' );
	}
	
	/**
	 * @return HasOne
	 */
	public function user (): HasOne
	{
		return $this->hasOne(User::class);
	}
	
	
	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function boss () : BelongsToMany
	{
		return $this->belongsToMany(self::class, 'subordinate_employees', 'subordinate_id', 'employee_id');
	}
	
}