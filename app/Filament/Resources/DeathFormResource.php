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
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Forms\Components\Toggle;
use Closure;

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
                        ->reactive()
                        ->getSearchResultsUsing(function(string $search) {
                            return Member::query()
                            ->search($search)
                            ->limit(50)->pluck('name', 'id');
                        })->getOptionLabelUsing(fn ($value): ?string => Member::find($value)?->name)
                        ->afterStateUpdated(function (Closure $set, $state, $context, Closure $get) {
                            $member = Member::findOrFail($state);
                            $funeralFees = $member->getFuneralFeesValue();
                            $latePaymentsAmount = $member->getUnpaidMembershipAmount();
                            $totalFormPayments = $member->getMemberBenefitsAmount();
                            $originalAmount = $member->getDeathFormValue();
                            // $refundForms = $member->refundForms->sum('amount');
                            $otherLatePaymentsAmount = $get('other_late_payments_amount');
                            $funeralFees = $get('funeral_fees');
                            $amount = (
                                $originalAmount - 
                                ( floatVal($totalFormPayments) + floatVal($latePaymentsAmount) + floatVal($otherLatePaymentsAmount) + floatVal($funeralFees) )
                                );
                            $set('funeral_fees', $funeralFees);
                            $set('late_payments_amount', $latePaymentsAmount);
                            $set('total_form_amounts', $totalFormPayments);
                            $set('original_amount', $originalAmount);
                            $set('amount', $amount);
                        })
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
                        ->numeric()
                        ->minValue(0)
                        ->reactive()
                        ->disabled(),
                    TextInput::make('other_late_payments_amount')
                        ->label('متأخرات أخرى')
                        ->reactive()
                        ->numeric()
                        ->default(0)
                        ->afterStateUpdated(function (Closure $set, $state, $context, Closure $get) {
                            $member = Member::findOrFail($get('member_id'));
                            $otherLatePaymentsAmount = $get('other_late_payments_amount');
                            $funeralFees = $get('funeral_fees');
                            $funeralFees = $get('funeral_fees');
                            $latePaymentsAmount = $get('late_payments_amount');
                            $totalFormPayments = $get('total_form_amounts');
                            $originalAmount = $get('original_amount');
                            $amount = (
                                $originalAmount - 
                                ( floatVal($totalFormPayments) + floatVal($latePaymentsAmount) + floatVal($otherLatePaymentsAmount) + floatVal($funeralFees) )
                                );
                            
                            $set('amount', $amount);
                        }),
                    TextInput::make('total_form_amounts')
                        ->label('مجموع المنح')
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->reactive()
                        ->disabled(),
                    Toggle::make('has_funeral_fees')->label('له مصاريف جنازة')->reactive()
                        ->afterStateUpdated(function (Closure $set, $state, $context, Closure $get) {
                            $member = Member::findOrFail($get('member_id'));
                            if ($state == true) {
                                $set('funeral_fees', $member->getFuneralFeesValue());
                                // $refundForms = $member->refundForms->sum('amount');
                                $otherLatePaymentsAmount = $get('other_late_payments_amount');
                                $funeralFees = $get('funeral_fees');
                                $latePaymentsAmount = $get('late_payments_amount');
                                $totalFormPayments = $get('total_form_amounts');
                                $originalAmount = $get('original_amount');
                                $amount = (
                                    $originalAmount - 
                                    ( floatVal($totalFormPayments) + floatVal($latePaymentsAmount) + floatVal($otherLatePaymentsAmount) + floatVal($funeralFees) )
                                );
                                
                                $set('amount', $amount);
                            } else {
                                $set('funeral_fees', null);
                                $amount = $get('amount') + $member->getFuneralFeesValue();
                                $set('amount',  $amount);
                            }
                            
                        }),
                    TextInput::make('funeral_fees')
                        ->label('مصاريف الجنازة')
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->visible(function(Closure $get) {
                            return $get('has_funeral_fees');
                        })
                        ->reactive()
                        ->disabled(),
                    TextInput::make('amount')
                        ->label('قيمة المنحة')
                        ->required()
                        ->numeric()
                        ->minValue(1),
                    TextInput::make('original_amount')
                        ->label('قيمة المنحة الابتدائية')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->disabled(),
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
                TextColumn::make('other_late_payments_amount')->label('متأخرات أخرى'),
                TextColumn::make('total_form_amounts')->label('مجموع المنح'),
                TextColumn::make('funeral_fees')->label('مصاريف الجنازة'),
                // TextColumn::make('original_amount')->label('صافي المنحة'),
                TextColumn::make('amount')->label('المبلغ'),
                BooleanColumn::make('pending')->getStateUsing(fn($record) => !$record->pending)->label('تمام الصرف'),
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
                Action::make('approve')
                    ->label('تم الصرف')
                    ->action(function($record) {
                    $record->update(['pending' => false]);
                })
                ->hidden(fn($record) => !$record->pending)
                ->requiresConfirmation()
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
