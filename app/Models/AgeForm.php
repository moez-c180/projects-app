<?php

namespace App\Models;

use app\Settings\SystemConstantsSettings;
use App\Traits\MemberFormTrait;
use App\Traits\HasMember;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AgeForm extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasMember;
    use MemberFormTrait;

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
        'notes',
        'member_form_id',
        'pending'
    ];

    protected $casts = [
        'form_date' => 'date',
        'pending' => 'boolean'
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

    public function formable(): MorphTo
    {
        return $this->morphTo();
    }
}
