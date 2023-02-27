<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BankNameResource\Pages;
use App\Filament\Resources\BankNameResource\RelationManagers;
use App\Filament\Resources\BankNameResource\RelationManagers\MembersRelationManager;
use App\Models\BankName;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use stdClass;
use Filament\Tables\Columns\TextColumn;

class BankNameResource extends Resource
{
    protected static ?string $model = BankName::class;
    protected static ?string $navigationGroup = 'البيانات الأساسية';
    protected static ?string $navigationLabel = 'البنوك ';
    protected static ?string $label = 'البنوك ';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Card::make()->schema([
                TextInput::make('name')
                    ->label('الاسم')
                    ->required()->maxLength(255)
            ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')->getStateUsing(static function (stdClass $rowLoop): string {
                    return (string) $rowLoop->iteration;
                }),
                TextColumn::make('name')->label('الاسم'),
                TextColumn::make('created_at')->label('تاريخ التسجيل')->dateTime('d-m-Y, H:i a')
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
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])->defaultSort('created_at', 'desc');
    }
    
    public static function getRelations(): array
    {
        return [
            MembersRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBankNames::route('/'),
            'create' => Pages\CreateBankName::route('/create'),
            'view' => Pages\ViewBankName::route('/{record}'),
            'edit' => Pages\EditBankName::route('/{record}/edit'),
        ];
    }    
}
