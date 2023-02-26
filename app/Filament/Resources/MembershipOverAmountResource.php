<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MembershipOverAmountResource\Pages;
use App\Filament\Resources\MembershipOverAmountResource\RelationManagers;
use App\Models\MembershipOverAmount;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use stdClass;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BooleanColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;

class MembershipOverAmountResource extends Resource
{
    protected static ?string $model = MembershipOverAmount::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'اشتراكات الأعضاء';

    protected static ?string $navigationLabel = 'زيادات الاشتراكات ';
    protected static ?string $label = 'زيادات الاشتراكات ';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('member.name')->disabled()->label('اسم العضو'),
                TextInput::make('amount')->disabled()->label('المبلغ'),
                DatePicker::make('refund_time')
                    ->default(Carbon::now()->today())
                    ->label('تاريخ الاسترداد'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')->getStateUsing(static function (stdClass $rowLoop): string {
                    return (string) $rowLoop->iteration;
                }),
                TextColumn::make('member.name')->label('اسم العضو'),
                TextColumn::make('member_unit')->label('وحدة العضو')->getStateUsing(function($record) {
                    return $record->member->getUnit()?->name;
                }),
                TextColumn::make('amount')->label('المبلغ')->description('جم'),
                BooleanColumn::make('refunded')->label('تم الاسترجاع')->getStateUsing(fn($record) => !is_null($record->refund_time)),
                TextColumn::make('refund_time')->label('تاريخ الاسترجاع')->dateTime('d-m-Y, H:i a'),
                TextColumn::make('membership_source')->label('القصاصة')->getStateUsing(function($record) {
                    if ($record->membershipSheetImport)
                    {
                        return $record->membershipSheetImport?->media->first()->file_name;
                    } else {
                        return "نقدي";
                    }
                }),
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
                EditAction::make(),
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
            'index' => Pages\ListMembershipOverAmounts::route('/'),
            'view' => Pages\ViewMembershipOverAmount::route('/{record}'),
            'edit' => Pages\EditMembershipOverAmount::route('/{record}/edit'),
        ];
    }    

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }
}
