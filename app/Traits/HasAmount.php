<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasAmount
{
    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value/100,
            set: fn ($value) => $value * 100,
        );
    }
}