<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FinancialBranchResource\Pages;
use App\Filament\Resources\FinancialBranchResource\RelationManagers;
use App\Models\FinancialBranch;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use stdClass;
use Filament\Forms\Components\DateTimePicker;

class FinancialBranchResource extends Resource
{
    protected static ?string $model = FinancialBranch::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'البيانات الأساسية';
    protected static ?string $navigationLabel = 'الأفرع المالية ';
    protected static ?string $label = 'الأفرع المالية ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('code')->required()->maxLength(255),
                TextInput::make('name')->required()->maxLength(255)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')->getStateUsing(static function (stdClass $rowLoop): string {
                    return (string) $rowLoop->iteration;
                }),
                TextColumn::make('code'),
                TextColumn::make('name'),
                TextColumn::make('created_at')->dateTime('d-m-Y, H:i a')
                    ->tooltip(function(TextColumn $column): ?string {
                        $state = $column->getState();
                        return $state->since();
                    })->sortable(),
            ])
            ->filters([
                //
            ])
            
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFinancialBranches::route('/'),
            'create' => Pages\CreateFinancialBranch::route('/create'),
            'view' => Pages\ViewFinancialBranch::route('/{record}'),
            'edit' => Pages\EditFinancialBranch::route('/{record}/edit'),
        ];
    }    
}
