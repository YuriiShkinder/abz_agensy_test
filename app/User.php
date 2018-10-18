<?php declare( strict_types = 1 );

namespace App;

use App\Http\Traits\HashTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 *
 * @package App
 * @property-read \App\Employee $employee
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @mixin \Eloquent
 */
class User extends Authenticatable {
	
	use Notifiable, HashTrait;
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'hash',
		'login',
		'password',
	];
	
	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'id',
		'password',
	];
	
	/**
	 * @return BelongsTo
	 */
	public function employee (): BelongsTo
	{
		return $this->belongsTo(Employee::class);
	}
}
