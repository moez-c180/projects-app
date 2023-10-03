<?php

namespace App\Models;

use App\Traits\HasAmount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MembershipSheetImportFailure extends Model
{
    use HasFactory;
    use SoftDeletes;
    // use HasAmount;
}
