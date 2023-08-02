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

class MemberUnitResource extends Resource
{
    protected static ?string $model = MemberUnit::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('member_id')
                        ->label('اسم العضو')
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListMemberUnits::route('/'),
            'create' => Pages\CreateMemberUnit::route('/create'),
            'view' => Pages\ViewMemberUnit::route('/{record}'),
            'edit' => Pages\EditMemberUnit::route('/{record}/edit'),
        ];
    }    
}
