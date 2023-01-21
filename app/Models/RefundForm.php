<?php

namespace App\Models;

use App\Traits\HasAmount;
use App\Traits\HasMember;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefundForm extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasMember;
    use HasAmount;

    protected $fillable = [
        'member_id',
        'amount',
        'notes',
    ];
    
}
