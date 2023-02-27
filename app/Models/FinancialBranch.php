<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Unit;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use App\Models\Member;

class FinancialBranch extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CascadeSoftDeletes;

    protected $fillable = [
        'name',
        'code'
    ];
    
    protected $cascadeDeletes = [
        'units',
        'members',
    ];

    /**
     * Get all of the units for the FinancialBranch
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
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
