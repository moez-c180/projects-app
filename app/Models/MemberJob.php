<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Unit;
use App\Traits\HasMember;

class MemberJob extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasMember;

    protected $fillable = [
        'member_id',
        'unit_id',
        'job_filled_date',
        'current_job'
    ];

    /**
     * Get the unit that owns the MemberJob
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
