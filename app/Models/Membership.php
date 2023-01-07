<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Member;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Membership extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'member_id',
        'month',
        'year',
        'amount',
        'notes',
    ];

    /**
     * Get the member that owns the Membership
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value/100,
            set: fn ($value) => $value * 100,
        );
    }

}
