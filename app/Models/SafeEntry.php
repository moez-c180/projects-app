<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\SafeEntryCategory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SafeEntry extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'safe_entry_category_id',
        'payable_type',
        'payable_id',
        'amount',
        'contact_name',
        'description',
        'operation_time'

    ];

    protected $casts = ['operation_time' => 'datetime'];

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value/100,
            set: fn ($value) => $value * 100,
        );
    }
    
    /**
     * Get the safeEntryCategory that owns the SafeEntry
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function safeEntryCategory(): BelongsTo
    {
        return $this->belongsTo(SafeEntryCategory::class);
    }

    public function payable(): MorphTo
    {
        return $this->morphTo('payable');
    }
}
