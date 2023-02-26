<?php

namespace App\Providers;

use App\Models\Membership;
use App\Observers\MembershipSheetImportObserver;
use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Filament\Navigation\NavigationGroup;
use App\Models\MembershipSheetImport;
use App\Models\MemberWallet;
use App\Models\RefundForm;
use App\Observers\MembershipObserver;
use App\Observers\MemberWalletObserver;
use App\Observers\RefundFormObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Filament::serving(function () {
            Filament::registerViteTheme('resources/css/filament.css');
        });

        // Filament Navigation Groups
        Filament::registerNavigationGroups([
            NavigationGroup::make()
                ->label('اشتراكات الأعضاء')
                ->collapsed(),
            NavigationGroup::make()
                ->label('المذكرات')
                ->collapsed(),
            NavigationGroup::make()
                ->label('البيانات الأساسية')
                ->collapsed(),
            NavigationGroup::make()
                ->label('Authentication')
                ->collapsed(),
            NavigationGroup::make()
                ->label('Settings')
                ->collapsed(),
            NavigationGroup::make()
                ->label('System')
                ->collapsed(),
            NavigationGroup::make()
                ->label('إعدادات')
                ->collapsed(),
        ]);

        Carbon::setWeekStartsAt(Carbon::SATURDAY);
        Carbon::setWeekEndsAt(Carbon::FRIDAY);

        // Macro on builder to use where like in shorter way in the code
        Builder::macro('whereLike', function (string $column, string $search) {
            return $this->orWhere($column, 'LIKE', '%'.$search.'%');
        });

        // Observers
        MembershipSheetImport::observe(MembershipSheetImportObserver::class);
        MemberWallet::observe(MemberWalletObserver::class);
        Membership::observe(MembershipObserver::class);
        RefundForm::observe(RefundFormObserver::class);

    }
}
