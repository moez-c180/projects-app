<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SafeEntryResource\Pages;
use App\Filament\Resources\SafeEntryResource\RelationManagers;
use App\Models\SafeEntry;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Closure;
use App\Models\SafeEntryCategory;
use App\Models\Unit;
use App\Models\Member;
use Filament\Tables\Columns\TextColumn;
use stdClass;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\BadgeColumn;

class SafeEntryResource extends Resource
{
    protected static ?string $model = SafeEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationLabel = 'حركة الخزينة ';
    protected static ?string $label = 'حركة الخزينة ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('safe_entry_type')
                    ->options([
                        'in' => 'وارد',
                        'out' => 'صادر',
                    ])
                    ->reactive()
                    ->required(),
                Select::make('safe_entry_category_id')
                    ->options(function(Closure $get) {
                        if ( !empty($get('safe_entry_type')) ) {
                            return SafeEntryCategory::whereCategory($get('safe_entry_type'))->pluck('name', 'id');
                        }
                    })
                    ->required()
                    ->hidden(fn (Closure $get) => $get('safe_entry_type') === null),
                TextInput::make('amount')
                    ->numeric()
                    ->minValue(1)
                    ->required(),
                TextInput::make('contact_name')->maxLength(255),
                Textarea::make('description')->required(),
                DateTimePicker::make('operation_time'),
                Select::make('payable_type')
                    ->options([
                        Unit::class => 'Unit',
                        Member::class => 'Member',
                    ])
                    ->reactive()
                    ->required(),
                Select::make('payable_id')
                    ->searchable()
                    ->getSearchResultsUsing(function(string $search, Closure $get) {
                        $searchable = $get('payable_type');
                        if ($searchable == Unit::class)
                        {
                            return Unit::query()->whereLike('name', $search)->limit(50)->pluck('name', 'id');
                        } elseif ($searchable == Member::class)
                        {
                            return Member::query()->whereLike('name', $search)->limit(50)->pluck('name', 'id');
                        } else {
                            return null;
                        }
                    })
                    ->required()
                
                

            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')->getStateUsing(static function (stdClass $rowLoop): string {
                    return (string) $rowLoop->iteration;
                }),
                BadgeColumn::make('safeEntryCategory.category')
                    ->colors([
                        'success' => static fn ($state): bool => $state === 'وارد',
                        'danger' => static fn ($state): bool => $state === 'صادر',
                    ])
                    ->getStateUsing(function(Model $record) {
                        return $record->safeEntryCategory->category == 'in' ? 'وارد' : 'صادر';
                    }),
                TextColumn::make('safeEntryCategory.name'),
                TextColumn::make('payable_type')
                    ->getStateUsing(function(Model $record) {
                        return $record->payable_type == Unit::class ? 'وحدة' : 'عضو';
                    }),
                TextColumn::make('payable_id')
                    ->getStateUsing(function(Model $record) {
                        return $record->payable->name;
                    }),
                TextColumn::make('amount')
                    ->description('جم'),
                TextColumn::make('description')->words(10),

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
            'index' => Pages\ListSafeEntries::route('/'),
            'create' => Pages\CreateSafeEntry::route('/create'),
            'view' => Pages\ViewSafeEntry::route('/{record}'),
            'edit' => Pages\EditSafeEntry::route('/{record}/edit'),
        ];
    }    
}
