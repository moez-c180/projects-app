<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Member;
use App\Traits\HasAmount;
use App\Traits\HasMember;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MemberForm extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasMember;
    // use HasAmount;

    protected $fillable = [
        'member_id',
        'formable_type',
        'formable_id',
        'amount'
    ];

    public function formable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeOfApproved(Builder $builder): Builder
    {
        return $builder->formable->where('pending', 0);
    }
}
