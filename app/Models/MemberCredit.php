<?php

namespace App\Models;

use App\Traits\HasAmount;
use App\Traits\HasMember;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberCredit extends Model
{
    use HasFactory;
    use HasAmount;
    use SoftDeletes;
    use HasMember;
}
