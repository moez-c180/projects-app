<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Member;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Category;
use App\Models\CategoryRank;

class Rank extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name'];

    /**
     * Get all of the members for the Rank
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }
    
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)
            ->using(CategoryRank::class);
    }
}
