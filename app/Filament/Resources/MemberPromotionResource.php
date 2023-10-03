<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberPromotionResource\Pages;
use App\Filament\Resources\MemberPromotionResource\RelationManagers;
use App\Models\MemberPromotion;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Card;
use App\Models\Member;
use Filament\Forms\Components\Select;
use App\Models\Rank;
use Filament\Forms\Components\DatePicker;
use stdClass;
use Filament\Tables\Columns\TextColumn;
use Closure;
use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Toggle;
use Filament\Pages\Actions\Action;

class MemberPromotionResource extends Resource
{
    protected static ?string $model = MemberPromotion::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationLabel = 'ترقي الأعضاء ';
    protected static ?string $label = 'ترقي الأعضاء ';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    Select::make('member_id')
                        ->label('العضو')
                        ->searchable()
                        ->getSearchResultsUsing(function(string $search) {
                            return Member::query()
                            ->search($search)
                            ->limit(50)->pluck('name', 'id');
                        })->getOptionLabelUsing(fn ($value): ?string => Member::find($value)?->name)
                        ->required(),
                    Select::make('rank_id')
                        ->label('الرتبة الحالية')
                        ->options(Rank::all()->pluck('name', 'id'))
                        ->required(),
                    DatePicker::make('promotion_date')
                        ->label('تاريخ الترقي')
                        ->required(),
                    Toggle::make('is_general_staff')->label('أ ح'),
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
                TextColumn::make('member.name')->label('اسم العضو'),
                TextColumn::make('rank.name')->label('الرتبة'),
                TextColumn::make('promotion_date')->label('تاريخ الترقي'),
                TextColumn::make('created_at')
                    ->label('تاريخ التسجيل')
                    ->dateTime('d-m-Y, H:i a')
                    ->tooltip(function(TextColumn $column): ?string {
                        $state = $column->getState();
                        return $state->since();
                    })
                    ->sortable()
            ])
            ->filters([
                SelectFilter::make('rank_id')
                    ->label('الرتبة')
                    ->options(Rank::all()->pluck('name', 'id'))
                    ->searchable(),
                DateFilter::make('created_at')
                    ->label('تاريخ التسجيل'),
                DateFilter::make('promotion_date')
                    ->label('تاريخ الترقي')
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
            'index' => Pages\ListMemberPromotions::route('/'),
            'create' => Pages\CreateMemberPromotion::route('/create'),
            'view' => Pages\ViewMemberPromotion::route('/{record}'),
            'edit' => Pages\EditMemberPromotion::route('/{record}/edit'),
        ];
    }    
}
