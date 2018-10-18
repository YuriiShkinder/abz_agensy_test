<?php
declare(strict_types=1);

namespace App\Http\Traits;


/**
 * Trait SlugTrait
 * @package App\Http\Traits
 */
trait SlugTrait
{
    protected static function boot () : void
    {
        static::creating(function($model)
        {
            $model->slug = str_slug($model->name);
        });
    }
}