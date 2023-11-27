<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasMember;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Unit;
use App\Observers\MemberUnitObserver;

class MemberUnit extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasMember;

    protected $fillable = [
        'member_id',
        'unit_id',
        'movement_date'
    ];

    protected $casts = [
        'movement_date' => 'date'
    ];

    public static function boot()
    {
        parent::boot();

        static::observe(MemberUnitObserver::class);
    }


    /**
     * Get the unit that owns the MemberUnit
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function previous()
    {
        return $this->find(--$this->id);
    }

}
