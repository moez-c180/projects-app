<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Member;
use App\Models\Unit;
use App\Models\Rank;
use App\Traits\HasMember;

class MemberPromotion extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasMember;
    
    protected $fillable = [
        'member_id',
        'rank_id',
        'promotion_date',
    ];

    protected $casts = [
        'promotion_date' => 'date'
    ];

    /**
     * Get the unit that owns the MemberPromotion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
    
    /**
     * Get the rank that owns the MemberPromotion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rank(): BelongsTo
    {
        return $this->belongsTo(Rank::class);
    }
}
