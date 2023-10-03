<?php

namespace App\Models;

use App\Traits\HasAmount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasMember;
use Illuminate\Database\Eloquent\Casts\Attribute;

class DeathForm extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasMember;
    // use HasAmount;

    protected $fillable = [
        'serial',
        'form_date',
        'member_id',
        'death_date',
        'human_tribute_car',
        'pall', // كفن
        'total_form_amounts',
        'late_payments_amount',
        'other_late_payments_amount', // متجمد
        'funeral_fees',
        'amount',
        'original_amount',
        'pending',
        'has_funeral_fees'
    ];

    protected $casts = [
        'death_date' => 'date',
        'pall' => 'boolean',
        'human_tribute_car' => 'boolean',
        'pending' => 'boolean',
        'has_funeral_fees' => 'boolean',
    ];
    
    // protected function totalFormAmounts(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => $value/100,
    //         set: fn ($value) => $value * 100,
    //     );
    // }
    
    // protected function funeralFees(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => $value/100,
    //         set: fn ($value) => $value * 100,
    //     );
    // }
    
    // protected function latePaymentsAmount(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => $value/100,
    //         set: fn ($value) => $value * 100,
    //     );
    // }
    
    // protected function otherLatePaymentsAmount(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => $value/100,
    //         set: fn ($value) => $value * 100,
    //     );
    // }
    
    // protected function originalAmount(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => $value/100,
    //         set: fn ($value) => $value * 100,
    //     );
    // }

}
