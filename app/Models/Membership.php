<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Member;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasAmount;
use App\Traits\HasMember;
use App\Models\FinancialBranch;
use App\Models\Unit;

class Membership extends Model
{

    const ONLY_MEMBERSHIP_VALUE = 1;
    const MEMBERSHIP_AND_LATE_MEMBERSHIP_VALUE = 2;
    const ONLY_LATE_MEMBERSHIP_VALUE = 3;

    use HasFactory;
    use SoftDeletes;
    use HasAmount;
    use HasMember;

    protected $fillable = [
        'member_id',
        'membership_date',
        'amount',
        'financial_branch_id',
        'unit_id',
        'notes',
    ];

    protected $casts = [
        'membership_date' => 'date'
    ];

    /**
     * Get the financialBranch that owns the Membership
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function financialBranch(): BelongsTo
    {
        return $this->belongsTo(FinancialBranch::class)->withTrashed();
    }
    
    /**
     * Get the unit that owns the Membership
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class)->withTrashed();
    }

}
