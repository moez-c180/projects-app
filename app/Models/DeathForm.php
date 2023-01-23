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
    use HasAmount;

    protected $fillable = [
        'serial',
        'form_date',
        'member_id',
        'death_date',
        'human_tribute_car',
        'pall',
        'total_form_amounts',
        'late_payments_amount',
        'funeral_fees',
        'amount',
        'final_amount',
    ];

    protected $casts = [
        'death_date' => 'date',
        'pall' => 'boolean',
        'human_tribute_car' => 'boolean'
    ];
    
    protected function totalFormAmounts(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value/100,
            set: fn ($value) => $value * 100,
        );
    }
    
    protected function funeralFees(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value/100,
            set: fn ($value) => $value * 100,
        );
    }
    
    protected function latePayments(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value/100,
            set: fn ($value) => $value * 100,
        );
    }
    
    protected function finalAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value/100,
            set: fn ($value) => $value * 100,
        );
    }

}
