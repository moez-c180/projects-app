<?php

namespace App\Filament\Resources\MemberResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use App\Models\Rank;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;

class PromotionsRelationManager extends RelationManager
{
    protected static string $relationship = 'promotions';
    protected static ?string $title = 'الترقي ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('rank_id')
                    ->label('الرتبة الحالية')
                    ->options(Rank::all()->pluck('name', 'id'))
                    ->required(),
                DatePicker::make('promotion_date')
                    ->label('تاريخ الترقي')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rank.name')->label('الرتبة'),
                TextColumn::make('promotion_date')->label('تاريخ الترقي'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
