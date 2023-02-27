<?php

namespace App\Filament\Resources\RankResource\RelationManagers;

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
                TextColumn::make('military_number')->label('الرقم العسكري')->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('seniority_number')->label('رقم الأقدمية')->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('rank.name')
                ->getStateUsing(function($record) {
                    return $record->getRankName();
                })
                ->label('الرتبة / الدرجة'),
                TextColumn::make('is_general_staff')
                    ->getStateUsing(function($record) {
                        return $record->is_general_staff ? Member::IS_GENERAL_STAFF : '';
                    })->label('أ ح'),
                TextColumn::make('name')->label('الاسم')->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('is_nco')
                    ->getStateUsing(function($record) {
                        return $record->category->is_nco ? Member::IS_NCO : Member::NON_NCO;
                    })->label('المشروع'),
                TextColumn::make('unit.name')->label('الوحدة'),
                TextColumn::make('financialBranch.name')->label('الفرع المالي'),
            ])
            ->filters([
                SelectFilter::make('unit_id')
                    ->label('الوحدة')
                    ->options(Unit::all()->pluck('name', 'id')),
                SelectFilter::make('financial_branch_id')
                    ->label('الفرع المالي')
                    ->options(Unit::all()->pluck('name', 'id')),
                SelectFilter::make('rank_id')
                    ->label('الرتبة / الدرجة')
                    ->options(Rank::all()->pluck('name', 'id')),
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
