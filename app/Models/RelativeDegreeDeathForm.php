<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Traits\HasAmount;

class RelativeDegreeDeathForm extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasAmount;

    protected $fillable = [
        'name',
        'amount',
        'in_cairo'
    ];
}
