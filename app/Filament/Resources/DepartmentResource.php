<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentResource\Pages;
use App\Filament\Resources\DepartmentResource\RelationManagers;
use App\Models\Department;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use stdClass;
use Filament\Tables\Columns\TextColumn;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'البيانات الأساسية';
    protected static ?string $navigationLabel = 'الأسلحة ';
    protected static ?string $label = 'الأسلحة ';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
            'index' => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
            'edit' => Pages\EditDepartment::route('/{record}/edit'),
        ];
    }    
}
