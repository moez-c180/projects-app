<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Member;
use App\Models\Unit;

class MemberPromotion extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'member_id',
        'rank_id',
        'promotion_date',
    ];

    /**
     * Get the member that owns the MemberPromotion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the unit that owns the MemberPromotion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
