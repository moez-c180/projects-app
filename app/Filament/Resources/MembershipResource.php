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
use App\Models\FinancialBranch;
use Closure;
use App\Models\Unit;
use Carbon\Carbon;
use App\Filament\Resources\MembershipResource\Pages\ImportMembershipsSheet;
use Filament\Notifications\Notification;

class MembershipResource extends Resource
{
    protected static ?string $model = Membership::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationLabel = 'تسجيل الاشتراكات للأعضاء ';
    protected static ?string $label = 'تسجيل الاشتراكات للأعضاء ';
    protected static ?string $navigationGroup = 'اشتراكات الأعضاء';
    protected static ?int $navigationSort = 1;

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
                        })
                        ->getOptionLabelUsing(fn ($value): ?string => Member::find($value)?->name)
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, $state, $context, Closure $get) {
                            $member = Member::findOrFail($get('member_id'));
                            if (!is_null($member->membership_start_date))
                            {
                                $financialBranchId = $member->getUnit()->financial_branch_id;
                                $unitId = $member->getUnit()->id;
                                $set('financial_branch_id', $financialBranchId);
                                $set('unit_id', $unitId);
                                $set('membership_value', $member->getSubscriptionValue());
                            } else {
                                
                                Notification::make()
                                    ->warning()
                                    ->title('العضو غير مشترك')
                                    ->body('هذا العضو غير مشترك و ليس له تاريخ بداية اشتراك.')
                                    ->send();
                                $set('member_id', null);
                            }
                        })
                        ->rules('exists:members,id')
                        ->required(),
                    TextInput::make('membership_value')
                        ->label('قيمة الاشتراك الفعلي')
                        // ->disabled()
                        ->visibleOn('create'),
                    TextInput::make('paid_amount')
                        ->label('المبلغ المدفوع')
                        ->minValue(1)
                        ->numeric()->required(),
                    DatePicker::make('membership_date')
                        ->default(Carbon::now()->startOfMonth())
                        ->label('تاريخ القسط')
                        ->required(),
                    Select::make('unit_id')
                        ->label('الوحدة')
                        ->searchable()
                        ->options(Unit::all()->pluck('name', 'id'))
                        ->required(),
                    Select::make('financial_branch_id')
                        ->label('الفرع المالي')
                        ->searchable()
                        ->options(FinancialBranch::all()->pluck('name', 'id'))
                        ->required(),
                    Textarea::make('notes')->label('ملاحظات'),
                ])->columns(2)
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
                TextColumn::make('member_financial_branch')->label('الفرع المالي للعضو')->getStateUsing(function($record) {
                    return $record->member->getUnit()?->financialBranch?->name;
                }),
                TextColumn::make('membership_date')->label('تاريخ القسط'),
                TextColumn::make('amount')->label('المبلغ المدفوع للقسط')->description('جم'),
                TextColumn::make('membership_value')->label('مبلغ القسط')->description('جم'),
                TextColumn::make('paid_amount')->label('المبلغ المدفوع')->description('جم'),
                TextColumn::make('unit.name')->label('الوحدة للقسط'),
                TextColumn::make('financialBranch.name')->label('الفرع المالي للقسط'),
                TextColumn::make('notes')->label('ملاحظات')->words(5),
                TextColumn::make('membership_source')->label('جهة التحصيل')->getStateUsing(function($record) {
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
