<?php

namespace App\Models;

use App\Traits\HasAmount;
use App\Traits\HasMember;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Traits\MemberFormTrait;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\RefundForm;

class FellowshipGrantForm extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasMember;
    use MemberFormTrait;

    protected $fillable = [
        'serial',
        'form_date',
        'member_id',
        'grant_amount',
        'amount',
        'pending',
    ];
}
