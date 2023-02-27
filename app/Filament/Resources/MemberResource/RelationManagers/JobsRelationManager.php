<?php

namespace App\Filament\Resources\MemberResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Models\Unit;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;

class JobsRelationManager extends RelationManager
{
    protected static string $relationship = 'jobs';

    protected static ?string $recordTitleAttribute = 'current_job';
    protected static ?string $title = 'التنقلات و الوظائف ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('current_job')
                    ->label('الوظيفة الحالية')
                    ->maxLength(255),
                Select::make('unit_id')
                    ->label('الوحدة')
                    ->options(Unit::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                DatePicker::make('job_filled_date')
                    ->label('تاريخ شغل الوظيفة')
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('current_job')->label('الوظيفة الحالية'),
                TextColumn::make('unit.name')->label('الوحدة'),
                TextColumn::make('job_filled_date')->label('تاريخ شغل الوظيفة'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()->after(function($record) {
                    $record->member()->update(['unit_id' => $record->unit_id]);
                    $record->member()->update(['financial_branch_id' => $record->unit->financial_branch_id]);
                }),
            ])
            ->actions([
                EditAction::make()->after(function($record) {
                    $record->member()->update(['unit_id' => $record->unit_id]);
                    $record->member()->update(['financial_branch_id' => $record->unit->financial_branch_id]);
                }),
                DeleteAction::make()->after(function($record) {
                    $record->member()->update(['unit_id' => null]);
                    $record->member()->update(['financial_branch_id' => null]);
                }),
            ])
            ->bulkActions([
                // DeleteBulkAction::make(),
            ]);
    }    
}
