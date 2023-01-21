<?php

namespace App\Models;

use App\Traits\HasAmount;
use App\Traits\HasMember;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ProjectClosureForm extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasAmount;
    use HasMember;

    protected $fillable = [
        'serial',
        'form_date',
        'member_id',
        'end_service_date',
        'total_subscription_payments',
        'total_forms_amount',
        'amount',
        'project_closure_reason_id',
    ];

    protected function totalSubscriptionPayments(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value/100,
            set: fn ($value) => $value * 100,
        );
    }
    
    protected function totalFormsAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value/100,
            set: fn ($value) => $value * 100,
        );
    }
}
