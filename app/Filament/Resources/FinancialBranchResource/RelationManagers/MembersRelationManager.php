<?php

namespace App\Filament\Resources\FinancialBranchResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use App\Models\Member;
use Filament\Tables\Filters\SelectFilter;
use App\Models\Unit;
use App\Models\Rank;
use Filament\Tables\Actions\Action;
use stdClass;
use Filament\Tables\Filters\TernaryFilter;
use App\Models\Category;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'members';

    protected static ?string $recordTitleAttribute = 'id';
    protected static ?string $title = 'الأعضاء';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')->getStateUsing(static function (stdClass $rowLoop): string {
                    return (string) $rowLoop->iteration;
                }),
                TextColumn::make('seniority_number')->label('رقم الأقدمية')->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('military_number')->label('الرقم العسكري')->searchable(isIndividual: true, isGlobal: false),
                
                TextColumn::make('rank.name')
                ->getStateUsing(function($record) {
                    return $record->getRankName();
                })
                ->label('الرتبة / الدرجة'),
                TextColumn::make('is_general_staff')
                    ->getStateUsing(function($record) {
                        return $record->is_general_staff ? Member::IS_GENERAL_STAFF : '';
                    })->label('أ ح'),
                TextColumn::make('promotion_date')
                    ->label('تاريخ الترقي')
                    ->getStateUsing(fn($record) => $record->memberPromotions()->latest()->first()?->promotion_date),
                TextColumn::make('name')->label('الاسم')->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('is_nco')
                    ->getStateUsing(function($record) {
                        return $record->category->is_nco ? Member::IS_NCO : Member::NON_NCO;
                    })->label('المشروع'),
                TextColumn::make('unit.name')->label('الوحدة'),
                TextColumn::make('member_unit_date')
                    ->label('تاريخ النقل')
                    ->getStateUsing(fn($record) => $record->memberUnits()->latest()->first()?->movement_date),
                TextColumn::make('member_job')
                    ->label('الوظيفة')
                    ->getStateUsing(fn($record) => $record->memberJobs()->latest()->first()?->position?->name),
                TextColumn::make('member_job_date')
                    ->label('تاريخ شغل الوظيفة')
                    ->getStateUsing(fn($record) => $record->memberJobs()->latest()->first()?->job_filled_date),
                TextColumn::make('financialBranch.name')->label('الفرع المالي'),
            ])
            ->filters([
                SelectFilter::make('unit_id')
                    ->label('الوحدة')
                    ->options(Unit::all()->pluck('name', 'id')),
                // SelectFilter::make('financial_branch_id')
                //     ->label('الفرع المالي')
                //     ->options(Unit::all()->pluck('name', 'id')),
                SelectFilter::make('rank_id')
                    ->label('الرتبة / الدرجة')
                    ->options(Rank::all()->pluck('name', 'id')),
                TernaryFilter::make('nco_co')
                    ->label('عاملين / شرفيين')
                    ->trueLabel('شرفيين')
                    ->falseLabel('عاملين')
                    ->queries(
                            true: fn (Builder $query) => $query->whereHas('category', fn($query) => $query->whereIsNco(true)),
                            false: fn (Builder $query) => $query->whereHas('category', fn($query) => $query->whereIsNco(false)),
                            blank: fn (Builder $query) => $query->withoutTrashed(),
                    ),
                SelectFilter::make('category_id')
                    ->label('الفئة')
                    ->options(Category::all()->pluck('name', 'id'))
                    ->searchable()
                    ->multiple(),
                TernaryFilter::make('is_dead')
                    ->label('متوفي')
                    ->trueLabel('متوفي')
                    ->falseLabel('غير متوفي')
                    ->default(false)
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('death_date'),
                        false: fn (Builder $query) => $query->whereNull('death_date'),
                        blank: fn (Builder $query) => $query,
                    )
            ])
            ->headerActions([
                
            ])
            ->actions([
                Action::make('view_member')
                    ->label('عرض')
                    ->url(fn($record) => route('filament.resources.members.view', $record))
                    ->openUrlInNewTab()

            ])
            ->bulkActions([
                
            ]);
    }    
}
