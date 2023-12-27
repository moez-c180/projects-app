<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectClosureReasonResource\Pages;
use App\Filament\Resources\ProjectClosureReasonResource\RelationManagers;
use App\Models\ProjectClosureReason;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use stdClass;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;

class ProjectClosureReasonResource extends Resource
{
    protected static ?string $model = ProjectClosureReason::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'البيانات الأساسية';
    protected static ?string $navigationLabel = 'قائمة أسباب تصفية المشروع ';
    protected static ?string $label = 'قائمة أسباب تصفية المشروع ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('reason')
                    ->label('السبب')->required()->maxLength(255)
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
                TextColumn::make('reason')->label('السبب'),
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
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjectClosureReasons::route('/'),
            'create' => Pages\CreateProjectClosureReason::route('/create'),
            'view' => Pages\ViewProjectClosureReason::route('/{record}'),
            'edit' => Pages\EditProjectClosureReason::route('/{record}/edit'),
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
