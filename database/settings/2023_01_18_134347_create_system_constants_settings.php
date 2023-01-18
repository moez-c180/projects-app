<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateSystemConstantsSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.subscription_fees_co_in_service', 75);
        $this->migrator->add('general.subscription_fees_co_out_service', 45);
        $this->migrator->add('general.subscription_fees_nco_in_service', 50);
        $this->migrator->add('general.subscription_fees_nco_out_service', 30);

        $this->migrator->add('general.co_death', 25000);
        $this->migrator->add('general.co_relative_death', 1000);
        $this->migrator->add('general.co_marriage', 1000);
        $this->migrator->add('general.co_relative_marriage', 1000);
        $this->migrator->add('general.co_age_honor_65', 2000);
        $this->migrator->add('general.co_age_honor_70', 2000);
        $this->migrator->add('general.co_grant', 10000);
        
        $this->migrator->add('general.nco_death', 17000);
        $this->migrator->add('general.nco_relative_death', 1000);
        $this->migrator->add('general.nco_marriage', 1000);
        $this->migrator->add('general.nco_relative_marriage', 1000);
        $this->migrator->add('general.nco_age_honor_65', 1000);
        $this->migrator->add('general.nco_age_honor_70', 1000);
        $this->migrator->add('general.nco_grant', 8000);
    }
}
