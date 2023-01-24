<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeathFormResource\Pages;
use App\Filament\Resources\DeathFormResource\RelationManagers;
use App\Models\DeathForm;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Models\Member;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use stdClass;

class DeathFormResource extends Resource
{
    protected static ?string $model = DeathForm::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'المذكرات';
    protected static ?string $navigationLabel = 'مذكرة وفاة العضو  ';
    protected static ?string $label = 'مذكرة وفاة العضو ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    TextInput::make('serial')
                        ->label('رقم المذكرة')->default(DeathForm::count() + 1)->required()->maxLength(255),
                    Select::make('member_id')
                        ->label(' العضو')
                        ->searchable()
                        ->getSearchResultsUsing(function(string $search) {
                            return Member::query()
                            ->whereLike('name', $search)
                            ->limit(50)->pluck('name', 'id');
                        })->getOptionLabelUsing(fn ($value): ?string => Member::find($value)?->name)
                        ->required(),
                    DatePicker::make('form_date')
                        ->label('تاريخ المذكرة')->required()
                        ->default(now())
                        ->maxDate(now()),
                    DatePicker::make('death_date')
                        ->label('تاريخ الوفاة')->required()
                        ->default(now())
                        ->maxDate(now()),
                    TextInput::make('late_payments_amount')
                        ->label('المتأخرات')
                        ->required()
                        ->numeric()
                        ->minValue(0),
                    TextInput::make('total_form_payments')
                        ->label('مجموع المنح')
                        ->required()
                        ->numeric()
                        ->minValue(0),
                    TextInput::make('funeral_fees')
                        ->label('مصاريف الجنازة')
                        ->required()
                        ->numeric()
                        ->minValue(0),
                    TextInput::make('final_amount')
                        ->label('صافي المنحة')
                        ->required()
                        ->numeric()
                        ->minValue(1),
                    TextInput::make('amount')
                        ->label('قيمة المنحة')
                        ->required()
                        ->numeric()
                        ->minValue(1),
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
                TextColumn::make('form_date')->label('تاريخ المذكرة'),
                TextColumn::make('death_date')->label('تاريخ الوفاة'),
                TextColumn::make('late_payments_amount')->label('المتأخرات'),
                TextColumn::make('total_form_amounts')->label('مجموع المنح'),
                TextColumn::make('funeral_fees')->label('مصاريف الجنازة'),
                TextColumn::make('final_amount')->label('صافي المنحة'),
                TextColumn::make('amount')->label('المبلغ'),
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
            'index' => Pages\ListDeathForms::route('/'),
            'create' => Pages\CreateDeathForm::route('/create'),
            'view' => Pages\ViewDeathForm::route('/{record}'),
            'edit' => Pages\EditDeathForm::route('/{record}/edit'),
        ];
    }    
}
