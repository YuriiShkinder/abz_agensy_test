<?php declare(strict_types=1);

namespace App\Http\Traits;
use Ramsey\Uuid\Uuid;


/**
 * Trait HashTrait
 * @package App\Http\Traits
 */
trait HashTrait
{
    protected static function boot () : void
    {
        static::creating(function($model)
        {
            $model->hash = Uuid::uuid4()->toString();
        });
    }
}