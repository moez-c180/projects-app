<?php

namespace App\Models;

use app\Settings\SystemConstantsSettings;
use App\Traits\HasMember;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgeForm extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasMember;

    const AGE_SIXY_FIVE_VALUE = 65;
    const AGE_SEVENTY_VALUE = 70;
    
    const AGE_FORM_VALUES = [
        self::AGE_SIXY_FIVE_VALUE => self::AGE_SIXY_FIVE_VALUE,
        self::AGE_SEVENTY_VALUE => self::AGE_SEVENTY_VALUE 
    ];
    
    protected $fillable = [
        'serial',
        'form_date',
        'member_id',
        'age_form_type',
        'amount',
        'notes'
    ];

    protected $casts = [
        'form_date' => 'date'
    ];

    public static function getAmount(int $age, bool $is_nco)
    {
        if (!in_array($age, array_values(self::AGE_FORM_VALUES)))
        {
            return 0;
        }

        if ($is_nco)
        {
            return match($age) {
                self::AGE_SEVENTY_VALUE => app(SystemConstantsSettings::class)->nco_age_honor_70,
                self::AGE_SIXY_FIVE_VALUE => app(SystemConstantsSettings::class)->nco_age_honor_65,
                default => 0
            };
        } else {
            return match($age) {
                self::AGE_SEVENTY_VALUE => app(SystemConstantsSettings::class)->co_age_honor_70,
                self::AGE_SIXY_FIVE_VALUE => app(SystemConstantsSettings::class)->co_age_honor_65,
                default => 0
            };
        }
    }
}
