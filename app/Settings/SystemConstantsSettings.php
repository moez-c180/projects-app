<?php

namespace app\Settings;

use Spatie\LaravelSettings\Settings;

class SystemConstantsSettings extends Settings
{

    public string $subscription_fees_co_in_service;
    public string $subscription_fees_co_out_service;
    public string $subscription_fees_nco_in_service;
    public string $subscription_fees_nco_out_service;

    public string $co_death;
    public string $co_relative_death;
    public string $co_marriage;
    public string $co_relative_marriage;
    public string $co_age_honor_65;
    public string $co_age_honor_70;
    public string $co_grant;
    
    public string $nco_death;
    public string $nco_relative_death;
    public string $nco_marriage;
    public string $nco_relative_marriage;
    public string $nco_age_honor_65;
    public string $nco_age_honor_70;
    public string $nco_grant;

    public static function group(): string
    {
        return 'general';
    }
}