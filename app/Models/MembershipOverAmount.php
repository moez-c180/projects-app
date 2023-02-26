<?php

namespace App\Models;

use App\Traits\HasAmount;
use App\Traits\HasMember;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Membership;
use App\Models\MembershipSheetImport;

class MembershipOverAmount extends Model
{
    use HasFactory;
    use HasMember;
    use HasAmount;

    protected $fillable = [
        'member_id',
        'amount',
        'refund_time',
        'membership_sheet_import_id',
    ];

    protected $casts = [
        'refund_time' => 'datetime'
    ];

    /**
     * Get the membership that owns the MembershipOverAmount
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class);
    }

    /**
     * Get the membershipSheetImport that owns the MembershipOverAmount
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function membershipSheetImport(): BelongsTo
    {
        return $this->belongsTo(MembershipSheetImport::class);
    }
}
