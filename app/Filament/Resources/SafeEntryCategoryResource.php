<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SafeEntryCategoryResource\Pages;
use App\Filament\Resources\SafeEntryCategoryResource\RelationManagers;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use stdClass;
use App\Models\SafeEntryCategory;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Card;

class SafeEntryCategoryResource extends Resource
{
    protected static ?string $model = SafeEntryCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'البيانات الأساسية';
    protected static ?string $navigationLabel = 'أنواع حركة الخزينة ';
    protected static ?string $label = 'أنواع حركة الخزينة ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('name')
                        ->label('الاسم')->required()->maxLength(255),
                    Select::make('category')
                        ->options([
                            'in' => 'وارد',
                            'out' => 'صادر',
                        ])
                        ->label('التصنيف')
                        ->required()
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
                TextColumn::make('category')->label('التصنيف'),
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListSafeEntryCategories::route('/'),
            'create' => Pages\CreateSafeEntryCategory::route('/create'),
            'edit' => Pages\EditSafeEntryCategory::route('/{record}/edit'),
        ];
    }    
}
