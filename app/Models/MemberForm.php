<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Member;
use App\Traits\HasMember;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MemberForm extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasMember;

    protected $fillable = [
        'member_id',
        'form_type',
        'form_id',
    ];

    // public function formable(): MorphTo
    // {
    //     return $this->morphTo(Form)
    // }
}
