<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

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

        Carbon::setWeekStartsAt(Carbon::SATURDAY);
        Carbon::setWeekEndsAt(Carbon::FRIDAY);

        // Macro on builder to use where like in shorter way in the code
        Builder::macro('whereLike', function (string $column, string $search) {
            return $this->orWhere($column, 'LIKE', '%'.$search.'%');
        });

    }
}
