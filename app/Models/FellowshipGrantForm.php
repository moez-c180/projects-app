<?php

namespace App\Models;

use App\Traits\HasAmount;
use App\Traits\HasMember;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Traits\MemberFormTrait;

class FellowshipGrantForm extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasMember;
    use HasAmount;
    use MemberFormTrait;

    protected $fillable = [
        'serial',
        'form_date',
        'member_id',
        'grant_amount',
        'amount',
        'pending',
    ];

    protected function grantAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value/100,
            set: fn ($value) => $value * 100,
        );
    }
}
