<?php

namespace App\Models;

use App\Traits\HasAmount;
use App\Traits\HasMember;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MembershipSheetImport;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberWallet extends Model
{
    use HasFactory;
    use HasAmount;
    use HasMember;

    const TYPE_DEPOSIT = 'deposit';
    const TYPE_WITHDRAW = 'withdraw';
    
    protected $fillable = [
        'member_id',
        'amount',
        'type',
        'membership_sheet_import_id'
    ];
    
    /**
     * Get the membershipSheetImport that owns the MemberWallet
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function membershipSheetImport(): BelongsTo
    {
        return $this->belongsTo(MembershipSheetImport::class);
    }

}
