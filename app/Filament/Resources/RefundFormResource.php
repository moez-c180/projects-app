<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RefundFormResource\Pages;
use App\Filament\Resources\RefundFormResource\RelationManagers;
use App\Models\RefundForm;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use App\Models\Member;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use stdClass;

class RefundFormResource extends Resource
{
    protected static ?string $model = RefundForm::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'المذكرات';
    protected static ?string $navigationLabel = 'رد المبالغ ';
    protected static ?string $label = 'رد المبالغ ';
    protected static ?int $navigationSort = 7;
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('member_id')
                        ->label(' العضو')
                        ->searchable()
                        ->getSearchResultsUsing(function(string $search) {
                            return Member::query()
                            ->whereLike('name', $search)
                            ->limit(50)->pluck('name', 'id');
                        })->getOptionLabelUsing(fn ($value): ?string => Member::find($value)?->name)
                        ->required()
                        ->reactive(),
                    TextInput::make('amount')
                        ->label('المبلغ')
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                    Textarea::make('notes')->label('ملاحظات')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')->getStateUsing(static function (stdClass $rowLoop): string {
                    return (string) $rowLoop->iteration;
                }),
                TextColumn::make('member.name')->label('اسم العضو')
                    ->url(fn ($record) => url('/admin/members/'.$record->member->id), true),
                TextColumn::make('amount')->label('المبلغ'),
                TextColumn::make('notes')->label('ملاحظات'),
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
            'index' => Pages\ListRefundForms::route('/'),
            'create' => Pages\CreateRefundForm::route('/create'),
            'view' => Pages\ViewRefundForm::route('/{record}'),
            'edit' => Pages\EditRefundForm::route('/{record}/edit'),
        ];
    }    
}
