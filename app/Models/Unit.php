<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\FinancialBranch;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Member;

class Unit extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'financial_branch_id',
        'name',
        'code'
    ];

    /**
     * Get the financialBranch that owns the Unit
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function financialBranch(): BelongsTo
    {
        return $this->belongsTo(FinancialBranch::class);
    }

    /**
     * Get all of the members for the FinancialBranch
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }
}
