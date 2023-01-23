<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DisabledFormResource\Pages;
use App\Filament\Resources\DisabledFormResource\RelationManagers;
use App\Models\DisabledForm;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use App\Models\Member;
use Filament\Tables\Columns\TextColumn;
use stdClass;

class DisabledFormResource extends Resource
{
    protected static ?string $model = DisabledForm::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'المذكرات';
    protected static ?string $navigationLabel = 'مذكرة عجز كلي  ';
    protected static ?string $label = 'مذكرة عجز كلي ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    TextInput::make('serial')
                        ->label('رقم المذكرة')->default(DisabledForm::count() + 1)->required()->maxLength(255),
                    DatePicker::make('form_date')
                        ->label('تاريخ المذكرة')->required()
                        ->default(now())
                        ->maxDate(now()),
                    Select::make('member_id')
                        ->label(' العضو')
                        ->searchable()
                        ->getSearchResultsUsing(function(string $search) {
                            return Member::query()
                            ->whereLike('name', $search)
                            ->limit(50)->pluck('name', 'id');
                        })->getOptionLabelUsing(fn ($value): ?string => Member::find($value)?->name)
                        ->required(),
                    TextInput::make('form_amount')
                        ->label('قيمة المنحة')
                        ->required()
                        ->numeric()
                        ->minValue(1),
                    TextInput::make('total_form_amounts')
                        ->label('منح تم صرفها')
                        ->required()
                        ->numeric()
                        ->minValue(0),
                    TextInput::make('amount')
                        ->label('صافي المستحق')
                        ->required()
                        ->numeric()
                        ->minValue(0),
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
                TextColumn::make('member.rank.name')
                    ->getStateUsing(fn($record) => $record->member->getRankName())
                    ->label('الرتبة'),
                TextColumn::make('member.name')->label('اسم العضو')
                    ->url(fn ($record) => url('/admin/members/'.$record->member->id), true),
                TextColumn::make('form_amount')->label('قيمة المنحة'),
                TextColumn::make('total_form_amounts')->label('منح تم صرفها'),
                TextColumn::make('amount')->label('صافي المستحق'),
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
            'index' => Pages\ListDisabledForms::route('/'),
            'create' => Pages\CreateDisabledForm::route('/create'),
            'view' => Pages\ViewDisabledForm::route('/{record}'),
            'edit' => Pages\EditDisabledForm::route('/{record}/edit'),
        ];
    }    
}
