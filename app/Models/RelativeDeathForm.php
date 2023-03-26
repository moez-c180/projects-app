<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasAmount;
use Illuminate\Database\Eloquent\Casts\Attribute;
use app\Settings\SystemConstantsSettings;
use App\Traits\HasMember;
use App\Traits\MemberFormTrait;

class RelativeDeathForm extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasAmount;
    use HasMember;
    use MemberFormTrait;

    protected $fillable = [
        'serial',
        'form_date',
        'death_date',
        'dead_name',
        'relative_type',
        'relative_death_degree_car_rent_id',
        'member_id',
        'amount',
        'car_rent',
        'sub_amount',
        'notes',
        'pending',

    ];

    protected function subAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value/100,
            set: fn ($value) => $value * 100,
        );
    }
    
    protected function carRent(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value/100,
            set: fn ($value) => $value * 100,
        );
    }

    public static function getAmount(bool $is_nco)
    {
        return match($is_nco) {
            true => app(SystemConstantsSettings::class)->nco_relative_death,
            false => app(SystemConstantsSettings::class)->co_relative_death,
            default => 0
        };
    }
}
