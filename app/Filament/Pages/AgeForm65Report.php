<?php

namespace App\Filament\Pages;

use App\Models\AgeForm;
use Filament\Pages\Page;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use stdClass;
use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use App\Models\Member;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\SelectFilter;
use Carbon\Carbon;

class AgeForm65Report extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.age-form-65-report';

    protected static ?string $navigationGroup = 'التقارير';
    
    protected static ?string $title = 'تقرير تكريم السن';

    protected static ?int $navigationSort = 1;

    protected function getTableQuery(): Builder 
    {
        return Member::query()
            ->whereRaw("TIMESTAMPDIFF(year,birth_date, now() ) = 65");
        
    }

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('#')->getStateUsing(static function (stdClass $rowLoop): string {
                return (string) $rowLoop->iteration;
            }),
            TextColumn::make('age_form_type')
                ->getStateUsing(function($record) {
                    return Carbon::parse($record->birth_date)->age;
                })
                ->label('السن'),
            TextColumn::make('is_nco')->label('عاملين / شرفيين')
                ->getStateUsing(function($record) {
                    return $record->category->is_nco ? 'شرفيين' : 'عاملين';
                }),
            TextColumn::make('military_number')->label('الرقم العسكري'),
            TextColumn::make('seniority_number')->label('رقم الأقدمية'),
            TextColumn::make('rank.name')->label('الرتبة'),
            TextColumn::make('is_general_staff')
                    ->getStateUsing(function($record) {
                        return $record->is_general_staff ? Member::IS_GENERAL_STAFF : '';
                    })->label('أ ح'),
            TextColumn::make('name')->label('الاسم'),
            TextColumn::make('department.name')->label('السلاح'),
            TextColumn::make('birth_date')->label('تاريخ الميلاد'),
            TextColumn::make('pension_date')
                ->label('تاريخ الإحالة للمعاش')
                ->toggleable(),
            TextColumn::make('pension_reason')
                ->label('سبب الإحالة للمعاش')
                ->toggleable(),
            TextColumn::make('address')
                ->label('العنوان')
                ->searchable(isIndividual: true, isGlobal: false)
                ->toggleable(),
            TextColumn::make('mobile_phone_number')
                ->label('رقم تليفون المحمول')
                ->searchable(isIndividual: true, isGlobal: false)
                ->toggleable(),
            TextColumn::make('review')
                ->label('مراجعة')
                ->toggleable(),
            
                
            
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            DateFilter::make('created_at')->label('تاريخ التسجيل'),
            TernaryFilter::make('age_form_type')
                ->label('السن')
                ->queries(
                    true: fn (Builder $query) => $query->whereRaw("TIMESTAMPDIFF(year,birth_date, now() ) = 65"),
                    false: fn (Builder $query) => $query->whereRaw("TIMESTAMPDIFF(year,birth_date, now() ) = 70"),
                    blank: fn (Builder $query) => $query,
                )
                ->trueLabel('65')
                ->falseLabel('70'),
            
            TernaryFilter::make('category.is_nco'),
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
