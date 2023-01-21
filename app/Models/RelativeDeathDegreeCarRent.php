<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Traits\HasAmount;

class RelativeDeathDegreeCarRent extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasAmount;

    protected $append = ['nameIsInCairo'];

    protected $fillable = [
        'name',
        'amount',
        'in_cairo'
    ];

    public function getNameIsInCairoAttribute()
    {
        return $this->name . " - " . ($this->in_cairo ? 'داخل القاهرة' : 'خارج القاهرة');
    }
}
