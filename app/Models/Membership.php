<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Member;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasAmount;
use App\Traits\HasMember;

class Membership extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasAmount;
    use HasMember;

    protected $fillable = [
        'member_id',
        'month',
        'year',
        'amount',
        'notes',
    ];

}
