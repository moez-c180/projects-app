<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasMember;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Unit;

class MemberUnit extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasMember;

    protected $fillable = [
        'member_id',
        'unit_id',
    ];

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
