<?php

namespace App\Models;

use App\Traits\HasAmount;
use App\Traits\HasMember;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class RefundForm extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasMember;

    protected $fillable = [
        'formable_type',
        'formable_id',
        'member_id',
        'amount',
        'notes',
    ];

    public function formable(): MorphTo
    {
        return $this->morphTo();
    }
    
}
