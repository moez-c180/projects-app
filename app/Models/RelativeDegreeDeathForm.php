<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class RelativeDegreeDeathForm extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'amount',
        'in_cairo'
    ];

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value/100,
            set: fn ($value) => $value * 100,
        );
    }
}
