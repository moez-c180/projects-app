<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberUnitResource\Pages;
use App\Filament\Resources\MemberUnitResource\RelationManagers;
use App\Models\MemberUnit;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use App\Models\Member;
use App\Models\Unit;
use Filament\Tables\Columns\TextColumn;
use stdClass;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;

class MemberUnitResource extends Resource
{
    protected static ?string $model = MemberUnit::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationLabel = 'تنقلات الأعضاء ';
    protected static ?string $label = 'تنقلات الأعضاء ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    Select::make('member_id')
                        ->label('العضو')
                        ->searchable()
                        ->getSearchResultsUsing(function(string $search) {
                            return Member::query()
                            ->search($search)
                            ->limit(50)->pluck('name', 'id');
                        })
                        ->getOptionLabelUsing(fn ($value): ?string => Member::find($value)?->name)
                        ->reactive()
                        ->rules('exists:members,id')
                        ->required(),
                    Select::make('unit_id')
                        ->label('الوحدة')
                        ->options(Unit::all()->pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                    DatePicker::make('movement_date')
                        ->label('تاريخ النقل')
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
                TextColumn::make('member.rank.name')->label('الرتبة'),
                TextColumn::make('member.seniority_number')->label('رقم الأقدمية'),
                TextColumn::make('member.military_number')->label('رقم العسكري'),
                TextColumn::make('member.name')->label('اسم العضو')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('unit.name')->label('الوحدة')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('movement_date')->label('تاريخ النقل')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')->label('تاريخ التسجيل')->dateTime('d-m-Y, H:i a')
                    ->tooltip(function(TextColumn $column): ?string {
                        $state = $column->getState();
                        return $state->since();
                    })->sortable(),
            ])
            ->filters([
                SelectFilter::make('movement_date')
                    ->label('تاريخ النقل')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListMemberUnits::route('/'),
            'create' => Pages\CreateMemberUnit::route('/create'),
            'view' => Pages\ViewMemberUnit::route('/{record}'),
            'edit' => Pages\EditMemberUnit::route('/{record}/edit'),
        ];
    }    
}
