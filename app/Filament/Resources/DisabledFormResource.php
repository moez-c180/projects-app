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
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Actions\Action;
use Closure;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;

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
                            ->search($search)
                            ->limit(50)->pluck('name', 'id');
                        })->getOptionLabelUsing(fn ($value): ?string => Member::find($value)?->name)
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, $state, $context, Closure $get) {
                            $member = Member::findOrFail($get('member_id'));
                            $formAmount = $member->getDeathFormValue();
                            $totalFormAmounts = $member->getMemberBenefitsAmount();
                            $latePaymentsAmount = $member->getUnpaidMembershipAmount();
                            $otherLatePaymentsAmount = $get('other_late_payments');
                            $set('form_amount', $formAmount);
                            $set('total_form_amounts', $totalFormAmounts);
                            $set('late_payments_amount', $latePaymentsAmount);
                            $set('other_late_payments', $otherLatePaymentsAmount);
                            $originalAmount = $get('form_amount');
                            $amount = (
                                $originalAmount - 
                                ( floatVal($totalFormAmounts) + floatVal($latePaymentsAmount) + floatVal($otherLatePaymentsAmount) ));
                            $set('amount', $amount);
                        }),
                    TextInput::make('form_amount')
                        ->label('قيمة المنحة')
                        ->required()
                        ->disabled()
                        ->numeric()
                        ->reactive(),
                    TextInput::make('late_payments_amount')
                        ->label('المتأخرات')
                        ->disabled()
                        ->numeric()
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, $state, $context, Closure $get) {
                            $member = Member::findOrFail($get('member_id'));
                            $formAmount = $member->getDeathFormValue();
                            $totalFormAmounts = $member->getMemberBenefitsAmount();
                            $latePaymentsAmount = $member->getUnpaidMembershipAmount();
                            $otherLatePaymentsAmount = $get('other_late_payments');
                            $set('form_amount', $formAmount);
                            $set('total_form_amounts', $totalFormAmounts);
                            $set('late_payments_amount', $latePaymentsAmount);
                            $set('other_late_payments', $otherLatePaymentsAmount);
                            $originalAmount = $get('form_amount');
                            $amount = (
                                $originalAmount - 
                                ( floatVal($totalFormAmounts) + floatVal($latePaymentsAmount) + floatVal($otherLatePaymentsAmount) ));
                            $set('amount', $amount);
                        }),
                    TextInput::make('other_late_payments')
                        ->label('متأخرات أخرى')
                        ->reactive()
                        ->numeric()
                        ->default(0)
                        ->afterStateUpdated(function (Closure $set, $state, $context, Closure $get) {
                            $member = Member::findOrFail($get('member_id'));
                            $formAmount = $member->getDeathFormValue();
                            $totalFormAmounts = $member->getMemberBenefitsAmount();
                            $latePaymentsAmount = $member->getUnpaidMembershipAmount();
                            $otherLatePaymentsAmount = $get('other_late_payments');
                            $set('form_amount', $formAmount);
                            $set('total_form_amounts', $totalFormAmounts);
                            $set('late_payments_amount', $latePaymentsAmount);
                            $set('other_late_payments', $otherLatePaymentsAmount);
                            $originalAmount = $get('form_amount');
                            $amount = (
                                $originalAmount - 
                                ( floatVal($totalFormAmounts) + floatVal($latePaymentsAmount) + floatVal($otherLatePaymentsAmount) ));
                            $set('amount', $amount);
                        }),
                    TextInput::make('total_form_amounts')
                        ->label('منح تم صرفها')
                        ->numeric()
                        ->disabled()
                        ->minValue(0)
                        ->reactive(),
                    TextInput::make('amount')
                        ->label('صافي المستحق')
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->reactive()
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
                TextColumn::make('form_amount')->label('قيمة المنحة'),
                TextColumn::make('total_form_amounts')->label('منح تم صرفها'),
                TextColumn::make('late_payments_amount')->label('المتأخرات'),
                TextColumn::make('other_late_payments')->label('متأخرات أخرى'),
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
            'index' => Pages\ListDisabledForms::route('/'),
            'create' => Pages\CreateDisabledForm::route('/create'),
            'view' => Pages\ViewDisabledForm::route('/{record}'),
            'edit' => Pages\EditDisabledForm::route('/{record}/edit'),
        ];
    }  
    
    
    public static function canViewAny(): bool
    {
        return auth()->user()->can(Permission::CAN_SEE_DISABLED_FORMS);
    }

    public static function canView(Model $record): bool
    {
        return auth()->user()->can(Permission::CAN_SEE_DISABLED_FORMS);
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can(Permission::CAN_CREATE_DISABLED_FORM);
    }

    public static function canEdit(Model $record): bool 
    {
        return auth()->user()->can(Permission::CAN_EDIT_DISABLED_FORM);
    }
    
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can(Permission::CAN_DELETE_DISABLED_FORM);
    }
}
