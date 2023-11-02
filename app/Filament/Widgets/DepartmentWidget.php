<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Member;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Database\Eloquent\Model;

class DepartmentWidget extends BaseWidget
{
    // protected static string $view = 'filament.widgets.department-widget';
    public ?Model $record = null;

    protected function getCards(): array
    {   
        $coInService = Member::query()
            ->whereNull('pension_date')
            ->whereHas('category', fn($query) => $query->where('is_nco', false))
            ->count();
        $coPension = Member::query()
            ->whereNull('pension_date')
            ->whereHas('category', fn($query) => $query->where('is_nco', false))
            ->count();;
        $totalCo = $coInService + $coPension;
        
        $ncoInService = Member::query()
            ->whereNull('pension_date')
            ->whereHas('category', fn($query) => $query->where('is_nco', true))
            ->count();
        $ncoPension = Member::query()
            ->whereNotNull('pension_date')
            ->whereHas('category', fn($query) => $query->where('is_nco', true))
            ->count();
        $totalNco = $ncoInService + $ncoPension;

        $totalInService = Member::whereNull('pension_date')->count();
        $totalPension = Member::whereNotNull('pension_date')->count();
        $sumInServicePension = $totalInService + $totalPension;

        return [
            Card::make('co_in_service', $coInService)
                ->label("عامليين خدمة")
                ->extraAttributes([
                    'class' => 'text-primary'
                ]),
            Card::make('co_pension', $coPension)
                ->label("عامليين معاش")
                ->extraAttributes([
                    'class' => 'text-primary'
                ]),
            Card::make('total_co', $totalCo)
                ->label("إجمالي عامليين")
                ->extraAttributes([
                    'class' => 'text-primary'
                ]),
            Card::make('nco_in_service', $ncoInService)
                ->label("شرفيين خدمة")
                ->extraAttributes([
                    'class' => 'text-primary'
                ]),
            Card::make('nco_pension', $ncoPension)
                ->label("شرفيين معاش")
                ->extraAttributes([
                    'class' => 'text-primary'
                ]),
            Card::make('total_nco', $totalNco)
                ->label("إجمالي شرفيين")
                ->extraAttributes([
                    'class' => 'text-primary'
                ]),
            
            Card::make('total_in_service', $totalInService)
                ->label("الإجمالي العام بالخدمة")
                ->extraAttributes([
                    'class' => 'text-primary'
                ]),
            Card::make('total_pension', $totalPension)
                ->label("الإجمالي العام شرفيين")
                ->extraAttributes([
                    'class' => 'text-primary'
                ]),
            Card::make('sum_in_service_pension', $sumInServicePension)
                ->label("الإجمالي العام")
                ->extraAttributes([
                    'class' => 'text-primary'
                ]),
            ];
        }
}
