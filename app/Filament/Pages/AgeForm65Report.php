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
            TextColumn::make('age_form_type')->label('السن'),
            TextColumn::make('is_nco')->label('عاملين / شرفيين')
                ->getStateUsing(function($record) {
                    return $record->category->is_nco ? 'شرفيين' : 'عاملين';
                }),
            TextColumn::make('military_number')->label('الرقم العسكري'),
            TextColumn::make('seniority_number')->label('رقم الأقدمية'),
            TextColumn::make('rank.name')->label('الرتبة'),
            TextColumn::make('category.name')->label('الفئة'),
            TextColumn::make('is_general_staff')
                    ->getStateUsing(function($record) {
                        return $record->is_general_staff ? Member::IS_GENERAL_STAFF : '';
                    })->label('أ ح'),
            TextColumn::make('name')->label('الاسم'),
            TextColumn::make('department.name')->label('السلاح'),
            TextColumn::make('file_number')->label('رقم الملف'),
            TextColumn::make('national_id_number')->label('الرقم القومي'),
            TextColumn::make('register_number')->label('رقم السجل'),
            TextColumn::make('review')->label('مراجعة'),
            TextColumn::make('birth_date')->label('تاريخ الميلاد')->getStateUsing(fn($record) => $record->birth_date->format('d-m-Y')),
            TextColumn::make('pension_reason')->label('قرار السببية'),
            TextColumn::make('pension_date')->label('تاريخ نهاية الخدمة'),
            TextColumn::make('mobile_phone_number')->label('رقم التليفون'),
            
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            DateFilter::make('created_at')->label('تاريخ التسجيل'),
            SelectFilter::make('age_form_type')
                ->label('السن')
                ->options([
                '65' => '65',
                '70' => '70',
                ]),
            TernaryFilter::make('category.is_nco'),
            // ->queries(
            //     true: fn (Builder $query) => $query->whereHas('member', fn($query) => $query->member()->where('is_nco', 1)),
            //     false: fn (Builder $query) => $query->whereHas('member', fn($query) => $query->member()->where('is_nco', 0)),
            //     blank: fn (Builder $query) => $query->whereHas('member', fn($query) => $query->member()->where('is_nco', 0)),
            //     // false: fn (Builder $query) => $query->member()->where('is_nco', 0),
            //     // blank: fn (Builder $query) => $query->member()->where('is_nco', 0),
            // )

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
