<?php

namespace App\Filament\Resources\MemberResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use App\Models\Unit;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\DatePicker;

class MemberUnitsRelationManager extends RelationManager
{
    protected static string $relationship = 'memberUnits';
    protected static ?string $title = 'التنقلات ';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('unit_id')
                    ->label('الوحدة')
                    ->options(Unit::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                DatePicker::make('movement_date')
                    ->label('تاريخ النقل')
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('unit.name')
                    ->label('الوحدة'),
                Tables\Columns\TextColumn::make('movement_date')
                    ->label('تاريخ النقل'),
                Tables\Columns\TextColumn::make('created_at')->label('تاريخ التسجيل')->dateTime('d-m-Y, H:i a')
                    ->tooltip(function(TextColumn $column): ?string {
                        $state = $column->getState();
                        return $state->since();
                    })->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->after(function(Model $record) {
                        $record->member()->update([
                            'unit_id' => $record->unit_id,
                            'financial_branch_id' => $record->unit->financial_branch_id,
                        ]);
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->after(function(Model $record) {
                    $record->member()->update([
                        'unit_id' => $record->unit_id,
                        'financial_branch_id' => $record->unit->financial_branch_id,
                    ]);
                }),
                Tables\Actions\DeleteAction::make()
                    ->after(function(Model $record) {
                        $record->member()->update([
                            'unit_id' => null,
                            'financial_branch_id' => null,
                        ]);
                    })
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])->defaultSort('created_at', 'desc');
    }    
}
