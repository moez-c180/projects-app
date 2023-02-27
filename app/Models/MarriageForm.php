<?php

namespace App\Models;

use App\Traits\HasAmount;
use App\Traits\HasMember;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use app\Settings\SystemConstantsSettings;
use App\Traits\HasFormTrait;

class MarriageForm extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasAmount;
    use HasMember;
    use HasFormTrait;

    protected $fillable = [
        'form_date',
        'serial',
        'is_relative',
        'member_id',
        'amount',
        'marriage_date',
        'relative_type',
        'relative_name',
        'notes',
    ];

    public static function getAmount(?bool $isRelative, bool $isNco)
    {
        if (is_null($isRelative))
        {
            return 0;
        }
        if ($isRelative)
        {
            return match($isNco) {
                true => app(SystemConstantsSettings::class)->nco_relative_marriage,
                false => app(SystemConstantsSettings::class)->co_relative_marriage,
                default => 0
            };
        } else {
            return match($isNco) {
                true => app(SystemConstantsSettings::class)->nco_marriage,
                false => app(SystemConstantsSettings::class)->co_marriage,
                default => 0
            };
        }
    }
    
}
