<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use App\Models\Member;
use stdClass;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use App\Models\Permission;

class Age56NcoReport extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.age56-nco-report';

    protected static ?string $navigationGroup = 'التقارير';
    
    protected static ?string $title = 'تقرير ٥٦ شرفيين';

    protected static ?int $navigationSort = 2;

    protected static array | string $middlewares = ['can:'.Permission::CAN_SEE_NCO_65_REPORTS];

    protected function getTableQuery(): Builder 
    {
        return Member::query()
            ->whereHas('category', function($query) {
                $query->where('is_nco', true);
            })
            ->whereRaw("TIMESTAMPDIFF(year,birth_date, now() ) = 56");
        
    }

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('#')->getStateUsing(static function (stdClass $rowLoop): string {
                return (string) $rowLoop->iteration;
            }),
            TextColumn::make('age_form_type')->label('السن'),
            TextColumn::make('category.is_nco')->label('عاملين / شرفيين')
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
