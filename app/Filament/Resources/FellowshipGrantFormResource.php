<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FellowshipGrantFormResource\Pages;
use App\Filament\Resources\FellowshipGrantFormResource\RelationManagers;
use App\Models\FellowshipGrantForm;
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
use stdClass;
use Filament\Tables\Columns\TextColumn;

class FellowshipGrantFormResource extends Resource
{
    protected static ?string $model = FellowshipGrantForm::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'المذكرات';
    protected static ?string $navigationLabel = 'مذكرة منحة الزمالة  ';
    protected static ?string $label = 'مذكرة منحة الزمالة ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    TextInput::make('serial')
                        ->label('رقم المذكرة')->default(FellowshipGrantForm::count() + 1)->required()->maxLength(255),
                    Select::make('member_id')
                        ->label(' العضو')
                        ->searchable()
                        ->getSearchResultsUsing(function(string $search) {
                            return Member::query()
                            ->search($search)
                            ->limit(50)->pluck('name', 'id');
                        })->getOptionLabelUsing(fn ($value): ?string => Member::find($value)?->name)
                        ->required(),
                    DatePicker::make('form_date')
                        ->label('تاريخ المذكرة')->required()
                        ->default(now())
                        ->maxDate(now()),
                    TextInput::make('grant_amount')
                        ->label('قيمة المنحة')
                        ->required()
                        ->numeric()
                        ->minValue(1),
                    TextInput::make('amount')
                        ->label('قيمة المنحة النهائية')
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
                TextColumn::make('grant_amount')->label('قيمة المنحة'),
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
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListFellowshipGrantForms::route('/'),
            'create' => Pages\CreateFellowshipGrantForm::route('/create'),
            'view' => Pages\ViewFellowshipGrantForm::route('/{record}'),
            'edit' => Pages\EditFellowshipGrantForm::route('/{record}/edit'),
        ];
    }    
}
