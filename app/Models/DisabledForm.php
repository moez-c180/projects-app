<?php

namespace App\Models;

use App\Traits\HasAmount;
use App\Traits\HasMember;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Traits\MemberFormTrait;

class DisabledForm extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasMember;
    // use HasAmount;
    use MemberFormTrait;

    protected $fillable = [
        'serial',
        'form_date',
        'member_id',
        'form_amount',
        'total_form_amounts',
        'late_payments_amount',
        'other_late_payments',
        'amount',
        'pending',
    ];

    protected $casts = [
        'form_date' => 'date',
        'pending' => 'boolean'
    ];

    // protected function totalFormAmounts(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => $value/100,
    //         set: fn ($value) => $value * 100,
    //     );
    // }
}
