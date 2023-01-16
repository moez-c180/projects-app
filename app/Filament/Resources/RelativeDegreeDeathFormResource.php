<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RelativeDegreeDeathFormResource\Pages;
use App\Filament\Resources\RelativeDegreeDeathFormResource\RelationManagers;
use App\Models\RelativeDegreeDeathForm;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;

class RelativeDegreeDeathFormResource extends Resource
{
    protected static ?string $model = RelativeDegreeDeathForm::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'البيانات الأساسية';
    protected static ?string $navigationLabel = 'قيم ايجار سيارة مذكرة الوفاة ';
    protected static ?string $label = 'قيم ايجار سيارة مذكرة الوفاة ';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('name')
                        ->label('درجة القرابة')->required()->maxLength(255),
                    TextInput::make('amount')
                        ->label('المبلغ')
                        ->minValue(1)
                        ->numeric()->required(),
                    Toggle::make('in_cairo')
                        ->label('داخل القاهرة')
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('درجة القرابة'),
                TextColumn::make('amount')
                    ->label('المبلغ'),
                BooleanColumn::make('in_cairo')
                    ->label('داخل القاهرة')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListRelativeDegreeDeathForms::route('/'),
            'create' => Pages\CreateRelativeDegreeDeathForm::route('/create'),
            'edit' => Pages\EditRelativeDegreeDeathForm::route('/{record}/edit'),
        ];
    }    
}
