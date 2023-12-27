<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RelativeDeathDegreeCarRentResource\Pages;
use App\Filament\Resources\RelativeDeathDegreeCarRentResource\RelationManagers;
use App\Models\RelativeDeathDegreeCarRent;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;

class RelativeDeathDegreeCarRentResource extends Resource
{
    protected static ?string $model = RelativeDeathDegreeCarRent::class;

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
                    ->label('المبلغ')
                    ->description('جم'),
                BooleanColumn::make('in_cairo')
                    ->label('داخل القاهرة')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListRelativeDeathDegreeCarRents::route('/'),
            'create' => Pages\CreateRelativeDeathDegreeCarRent::route('/create'),
            'edit' => Pages\EditRelativeDeathDegreeCarRent::route('/{record}/edit'),
        ];
    }    


    public static function canViewAny(): bool
    {
        return auth()->user()->can(Permission::CAN_ACCESS_SYSTEM_CORE_VALUES);
    }

    public static function canView(Model $record): bool
    {
        return auth()->user()->can(Permission::CAN_ACCESS_SYSTEM_CORE_VALUES);
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can(Permission::CAN_ACCESS_SYSTEM_CORE_VALUES);
    }

    public static function canEdit(Model $record): bool 
    {
        return auth()->user()->can(Permission::CAN_ACCESS_SYSTEM_CORE_VALUES);
    }
    
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can(Permission::CAN_ACCESS_SYSTEM_CORE_VALUES);
    }
}
