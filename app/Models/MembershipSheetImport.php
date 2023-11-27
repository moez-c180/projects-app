<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Models\User;
use App\Models\Membership;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\MembershipOverAmount;
use Illuminate\Support\Facades\DB;

class MembershipSheetImport extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    const MEDIA_COLLECTION_NAME = 'membership-sheet';

    protected $casts = [
        'processed' => 'boolean',
        'processing_start_time' => 'datetime',
        'processing_finish_time' => 'datetime',
        'membership_date' => 'date',
        'on_pension' => 'boolean',
    ];

    protected $fillable = [
        'user_id',
        'processed',
        'processing_start_time',
        'processing_finish_time',
        'membership_date',
        'on_pension'
    ];

    protected $with = ['media'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the memberships for the MembershipSheetImport
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }
    
    public function rollback()
    {
        $membershipSheetImport = $this;
        DB::transaction(function () use ($membershipSheetImport) {
            $membershipSheetImport->memberships->each(fn($record) => $record->delete());
            // $membershipSheetImport->membershipOverAmounts()->delete();
            $membershipSheetImport->update([
                'processing_start_time' => NULL,
                'processing_finish_time' => NULL,
                'processed' => 0,
            ]);
        });
    }
}
