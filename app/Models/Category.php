<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Member;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Rank;
use App\Models\CategoryRank;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'is_nco'
    ];

    /**
     * Get all of the members for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }
    
    public function ranks(): BelongsToMany
    {
        return $this->belongsToMany(Rank::class)
            ->using(CategoryRank::class);
    }
}
