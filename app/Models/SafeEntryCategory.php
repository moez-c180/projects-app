<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SafeEntry;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SafeEntryCategory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'category'];

    /**
     * Get all of the safeEntries for the SafeEntryCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function safeEntries(): HasMany
    {
        return $this->hasMany(SafeEntry::class);
    }
}
