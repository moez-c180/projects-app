<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RelativeDeathFormResource\Pages;
use App\Filament\Resources\RelativeDeathFormResource\RelationManagers;
use App\Models\RelativeDeathForm;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use App\Models\Member;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Closure;
use Filament\Forms\Components\DatePicker;
use App\Models\RelativeDeathDegreeCarRent;
use Filament\Tables\Columns\TextColumn;
use stdClass;

class RelativeDeathFormResource extends Resource
{
    protected static ?string $model = RelativeDeathForm::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'المذكرات';
    protected static ?string $navigationLabel = 'مذكرة وفاة أحد المعولين  ';
    protected static ?string $label = 'مذكرة وفاة أحد المعولين ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    TextInput::make('serial')
                        ->label('رقم المذكرة')->default(RelativeDeathForm::count() + 1)->required()->maxLength(255),
                    Select::make('member_id')
                        ->label(' العضو')
                        ->searchable()
                        ->getSearchResultsUsing(function(string $search) {
                            return Member::query()
                            ->whereLike('name', $search)
                            ->limit(50)->pluck('name', 'id');
                        })->getOptionLabelUsing(fn ($value): ?string => Member::find($value)?->name)
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, $state, $context, Closure $get) {
                            $member = Member::findOrFail($get('member_id'));
                            $amount = RelativeDeathForm::getAmount($member->category->is_nco);
                            $set('amount', $amount);
                            $set('sub_amount', $amount);
                        }),
                    TextInput::make('amount')
                        ->label('قيمة المنحة')
                        ->required()
                        ->disabled(),
                    DatePicker::make('form_date')
                        ->label('تاريخ المذكرة')->required()
                        ->default(now())
                        ->maxDate(now()),
                    DatePicker::make('death_date')
                        ->label('تاريخ الوفاة')->required()
                        ->default(now())
                        ->maxDate(now()),
                    TextInput::make('dead_name')
                        ->label('اسم المتوفي')->required()->maxLength(255),
                    TextInput::make('relative_type')
                        ->label('درجة القرابة')->required()->maxLength(255),
                    Toggle::make('car_will_be_used')
                        ->reactive()
                        ->label('سيتم استخدام السيارة')
                        ->visible(fn(Closure $get) => $get('member_id')),
                    Select::make('relative_death_degree_car_rent_id')
                        ->options(RelativeDeathDegreeCarRent::all()->pluck('nameIsInCairo', 'id'))
                        ->label('تحديد قيمة إيجار السيارة')
                        ->required()
                        ->visible(fn(Closure $get) => $get('car_will_be_used'))
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, $state, $context, Closure $get) {
                            $carRent = RelativeDeathDegreeCarRent::find($get('relative_death_degree_car_rent_id'))->amount;
                            $set('car_rent', $carRent);
                            $set('sub_amount', ($get('amount') - $get('car_rent')) );
                        })
                        ,
                        
                    TextInput::make('car_rent')->label('إيجار السيارة')
                        ->disabled()
                        ->visible(fn(Closure $get) => $get('car_will_be_used')),
                    TextInput::make('sub_amount')->required()->label('صافي قيمة المنحة')->disabled(),
                    
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
                TextColumn::make('serial')->label('رقم المذكرة'),
                TextColumn::make('member.rank.name')
                    ->getStateUsing(fn($record) => $record->member->getRankName())
                    ->label('الرتبة'),
                TextColumn::make('member.name')->label('اسم العضو')
                    ->url(fn ($record) => url('/admin/members/'.$record->member->id), true),
                TextColumn::make('dead_name')->label('اسم المتوفي'),
                TextColumn::make('relative_type')->label('صلة القرابة'),
                TextColumn::make('sub_amount')->label('صافي المنحة')->description('جم'),
                TextColumn::make('car_rent')->label('إيجار سيارة')->description('جم'),
                TextColumn::make('amount')->label('قيمة المنحة')->description('جم'),
                TextColumn::make('form_date')->label('تاريخ المذكرة'),
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
            'index' => Pages\ListRelativeDeathForms::route('/'),
            'create' => Pages\CreateRelativeDeathForm::route('/create'),
            'view' => Pages\ViewRelativeDeathForm::route('/{record}'),
            'edit' => Pages\EditRelativeDeathForm::route('/{record}/edit'),
        ];
    }    
}
