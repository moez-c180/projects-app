<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FinancialBranchResource\Pages;
use App\Filament\Resources\FinancialBranchResource\RelationManagers;
use App\Filament\Resources\FinancialBranchResource\RelationManagers\MembersRelationManager;
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
use Filament\Forms\Components\Card;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;

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
                Card::make()->schema([
                    TextInput::make('code')
                        ->label('الكود')->required()->maxLength(255),
                    TextInput::make('name')
                        ->label('الاسم')->required()->maxLength(255)
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
                TextColumn::make('id'),
                TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->label('الكود'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('الاسم'),
                    TextColumn::make('nco_members_count')
                    ->label('# الشرفيين')
                    ->getStateUsing(fn($record) => $record->members()->whereNull('death_date')->ofNco(true)->count())
                    ->sortable(),
                TextColumn::make('co_members_count')
                    ->label('# العامليين')
                    ->getStateUsing(fn($record) => $record->members()->whereNull('death_date')->ofNco(false)->count())
                    ->sortable(),
                TextColumn::make('members_count')
                    ->label('الإجمالي')
                    ->getStateUsing(fn($record) => $record->members()->whereNull('death_date')->count())
                    ->sortable(),
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
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                ExportAction::make()->exports([
                    ExcelExport::make()->fromTable()->except([
                        '#'
                    ]),
                ])
            ]);
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
            'index' => Pages\ListFinancialBranches::route('/'),
            'create' => Pages\CreateFinancialBranch::route('/create'),
            'view' => Pages\ViewFinancialBranch::route('/{record}'),
            'edit' => Pages\EditFinancialBranch::route('/{record}/edit'),
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
