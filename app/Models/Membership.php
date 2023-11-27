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
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\MembershipSheetImport;

class Membership extends Model
{

    const ONLY_MEMBERSHIP_VALUE = 1;
    const MEMBERSHIP_AND_LATE_MEMBERSHIP_VALUE = 2;
    const ONLY_LATE_MEMBERSHIP_VALUE = 3;

    use HasFactory;
    use SoftDeletes;
    // use HasAmount;
    use HasMember;

    protected $fillable = [
        'member_id',
        'membership_date',
        'amount',
        'unit_id',
        'financial_branch_id',
        'notes',
        'membership_value',
        'paid_amount',
        'approved',
        'membership_sheet_import_id',
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

    public function scopeOfApproved(Builder $builder): Builder
    {
        return $builder->whereApproved(true);
    }

    // protected function paidAmount(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => $value/100,
    //         set: fn ($value) => $value * 100,
    //     );
    // }
    
    // protected function membershipValue(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => $value/100,
    //         set: fn ($value) => $value * 100,
    //     );
    // }

    /**
     * Get the membershipSheetImport that owns the Membership
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function membershipSheetImport(): BelongsTo
    {
        return $this->belongsTo(MembershipSheetImport::class);
    }

}
