<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MembershipResource\Pages;
use App\Filament\Resources\MembershipResource\RelationManagers;
use App\Models\Membership;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use App\Models\Member;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Savannabits\Flatpickr\Flatpickr;
use Filament\Tables\Columns\TextColumn;
use stdClass;
use Filament\Forms\Components\Card;

class MembershipResource extends Resource
{
    protected static ?string $model = Membership::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationLabel = 'تسجيل الاشتراكات للأعضاء ';
    protected static ?string $label = 'تسجيل الاشتراكات للأعضاء ';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    Select::make('member_id')
                        ->label('اسم العضو')
                        ->searchable()
                        ->getSearchResultsUsing(function(string $search) {
                            return Member::query()
                            ->whereLike('name', $search)
                            ->limit(50)->pluck('name', 'id');
                        })->getOptionLabelUsing(fn ($value): ?string => Member::find($value)?->name)
                        ->required(),
                    TextInput::make('amount')
                        ->label('المبلغ')
                        ->minValue(1)
                        ->numeric()->required(),
                    Select::make('year')
                        ->label('السنة')
                        ->options(function() {
                            $years = [];
                            for($year = 1980; $year <= date('Y'); $year++)
                            {
                                $years[$year] = $year; 
                            }
                            return $years;
                        })
                        ->required()
                        ->searchable(),
                    Select::make('month')
                        ->label('الشهر')
                        ->options(function() {
                            $months = [];
                            for ($m=1; $m<=12; $m++) {
                                
                                $months[$m] = date('F', mktime(0,0,0,$m, 1, date('Y')));
                            }
                            return $months;
                        })
                        ->required(),
                    Textarea::make('notes')->label('ملاحظات'),
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
                TextColumn::make('member.name')->label('اسم العضو'),
                TextColumn::make('month')->label('الشهر'),
                TextColumn::make('year')->label('السنة'),
                TextColumn::make('amount')->label('المبلغ'),
                TextColumn::make('notes')->label('ملاحظات')->words(5),
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
            'index' => Pages\ListMemberships::route('/'),
            'create' => Pages\CreateMembership::route('/create'),
            'view' => Pages\ViewMembership::route('/{record}'),
            'edit' => Pages\EditMembership::route('/{record}/edit'),
        ];
    }    
}
