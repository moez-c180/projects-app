<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Database\Query\Builder;
use App\Models\Member;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use App\Models\Membership;
use Carbon\Carbon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use stdClass;
use Filament\Tables\Filters\TernaryFilter;
use App\Models\Unit;
use App\Models\FinancialBranch;
use Filament\Tables\Columns\BooleanColumn;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use App\Models\Permission;

class MembersDidnotPayMembershipReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected $queryString = [
        'tableFilters',
        'tableSortColumn',
        'tableSortDirection',
        'tableSearchQuery' => ['except' => ''],
        'tableColumnSearchQueries',
    ];

    protected static array | string $middlewares = ['can:'.Permission::CAN_SEE_MEMBERS_WITH_LATE_PAYMENTS_REPORT];

    public static function canView(): bool
    {
        return false;
    }

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.members-didnot-pay-membership-report';

    protected static ?string $navigationGroup = 'التقارير';

    protected static ?string $title = 'تقرير بالأعضاء اللذين لم يتم التحصيل (متأخرات)';

    protected function getTableQuery(): Builder 
    {
        return Member::query()
            ->with('memberUnits', 'category')
            ->whereDoesntHave('memberships');
            // ->orWhereHas('memberships', function($query) {
            //     $date = request('tableFilters')['membership_date']['membership_date'] ?? Carbon::today()->month();
            //     $query->whereMonth('membership_date', '!=', $date);
            // });
    }

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('#')->getStateUsing(static function (stdClass $rowLoop): string {
                return (string) $rowLoop->iteration;
            }),
            TextColumn::make('name')
                ->label('الاسم'),
            TextColumn::make('military_number')
                ->label('الرقم العسكري'),
            TextColumn::make('seniority_number')
                ->label('رقم الأقدمية'),
            TextColumn::make('rank.name')
                ->label('الرتبة / الدرجة'),
            TextColumn::make('unit_name')
                ->getStateUsing(fn($record) => $record->unit?->name)
                ->label('الوحدة'),
            TextColumn::make('financial_branch_name')
                ->getStateUsing(fn($record) => $record->financialBranch?->name)
                ->label('الفرع المالي'),
            BooleanColumn::make('is_nco')
                    ->getStateUsing(function($record) {
                        return $record->category->is_nco;
                    })
                    ->label('شرفيين'),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            // SelectFilter::make('is_nco'),
            SelectFilter::make('unit_id')
                ->label('الوحدة')
                ->options(Unit::all()->pluck('name', 'id'))
                ->searchable(),
            SelectFilter::make('financial_branch_id')
                ->label('الفرع المالي')
                ->options(FinancialBranch::all()->pluck('name', 'id'))
                ->searchable(),
            TernaryFilter::make('work_pension')
                ->label('خدمة / معاش')
                ->trueLabel('خدمة')
                ->falseLabel('معاش')
                ->queries(
                        true: fn (Builder $query) => $query->whereNull('pension_date'),
                        false: fn (Builder $query) => $query->whereNotNull('pension_date'),
                        blank: fn (Builder $query) => $query,
                ),
            TernaryFilter::make('nco_co')
                ->label('عاملين / شرفيين')
                ->trueLabel('شرفيين')
                ->falseLabel('عاملين')
                ->queries(
                        true: fn (Builder $query) => $query->whereHas('category', fn($query) => $query->whereIsNco(true)),
                        false: fn (Builder $query) => $query->whereHas('category', fn($query) => $query->whereIsNco(false)),
                        blank: fn (Builder $query) => $query,
                ),
            // DateFilter::make('membership_date')
            Filter::make('membership_date')
                ->form([
                    DatePicker::make('membership_date')
                    ->default(Carbon::today()->startOfMonth()),
                ])->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['membership_date'],
                            function(Builder $query, $date) {
                                $query->orWhereHas('memberships', function($query) use ($date) {
                                    $query->whereMonth('membership_date', '!=', $date);
                                });
                            }
                        );
                    })
        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [
            ExportAction::make('export')->exports([
                ExcelExport::make()->fromTable()->except([
                    '#',
                ]),                
            ])
        ];
    }
}
