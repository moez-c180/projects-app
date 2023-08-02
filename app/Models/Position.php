<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\MemberJob;

class Position extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = ['name'];

    public function memberJobs(): HasMany
    {
        return $this->hasMany(MemberJob::class);
    }
}
