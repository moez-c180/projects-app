<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RefundFormResource\Pages;
use App\Filament\Resources\RefundFormResource\RelationManagers;
use App\Models\AgeForm;
use App\Models\DeathForm;
use App\Models\FellowshipGrantForm;
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
use Filament\Forms\Components\Card;
use Closure;
use Filament\Notifications\Notification;
use App\Models\MemberForm;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\MorphToSelect\Type;
use App\Models\MarriageForm;
use App\Models\ProjectClosureForm;
use App\Models\DisabledForm;
use App\Models\RelativeDeathForm;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;

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
                Card::make([
                    Select::make('member_id')
                        ->label(' العضو')
                        ->searchable()
                        ->getSearchResultsUsing(function(string $search) {
                            return Member::query()
                            ->search($search)
                            ->limit(50)->pluck('name', 'id');
                        })->getOptionLabelUsing(fn ($value): ?string => Member::find($value)?->name)
                        ->required()
                        ->reactive(),
                    MorphToSelect::make('formable')
                        ->label('المذكرة')
                        ->types([
                            Type::make(AgeForm::class)
                                ->titleColumnName('serial')
                                ->label('مذكرة تكريم السن'),
                            Type::make(DeathForm::class)
                                ->titleColumnName('serial')
                                ->label('مذكرة وفاة عضو'),
                            Type::make(DisabledForm::class)
                                ->titleColumnName('serial')
                                ->label('مذكرة عجز كلي'),
                            Type::make(FellowshipGrantForm::class)
                                ->titleColumnName('serial')
                                ->label('مذكرة منحة زمالة'),
                            Type::make(MarriageForm::class)
                                ->titleColumnName('serial')
                                ->label('مذكرة هدية زواج '),
                            Type::make(ProjectClosureForm::class)
                                ->titleColumnName('serial')
                                ->label('مذكرة تصفية مشروع'),
                            Type::make(RelativeDeathForm::class)
                                ->titleColumnName('serial')
                                ->label('مذكرة وفاة أحد المعولين'),
                        ])
                    ,
                    // Select::make('member_form_id')
                    //     ->relationship('formable', 'formable.serial'),
                    TextInput::make('amount')
                        ->label('المبلغ')
                        ->numeric()
                        ->reactive()
                        ->minValue(1)
                        ->required()
                        ->visible(fn(Closure $get) => !is_null($get('member_id')))
                        ->afterStateUpdated(function(Closure $get, Closure $set) {
                            $member = Member::findOrFail($get('member_id'));
                            if (!$member)
                            {
                                return false;
                            }

                            if ($member->wallet < $get('amount'))
                            {
                                $set('amount', null);
                                Notification::make()
                                    ->danger()
                                    ->title('لا يمكن إتمام العملية')
                                    ->body('عفواًٍ لا يمكن تنفيذ العملية حيث أن العضو ليس لديه رصيد كاف.')
                                    ->send();
                                return false;
                            }

                            if (count($member->getUnpaidMembershipMonths()) != 0)
                            {
                                $set('amount', null);
                                Notification::make()
                                    ->danger()
                                    ->title('لا يمكن إتمام العملية')
                                    ->body('عفواًٍ لا يمكن تنفيذ العملية حيث أن العضو عليه اشتراكات مستحقة.')
                                    ->send();
                                return false;
                            }
                        }),
                    Textarea::make('notes')->label('ملاحظات')
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

    public static function canViewAny(): bool
    {
        return auth()->user()->can(Permission::CAN_SEE_REFUND_FORMS);
    }

    public static function canView(Model $record): bool
    {
        return auth()->user()->can(Permission::CAN_SEE_REFUND_FORMS);
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can(Permission::CAN_CREATE_REFUND_FORM);
    }

    public static function canEdit(Model $record): bool 
    {
        return auth()->user()->can(Permission::CAN_EDIT_REFUND_FORM);
    }
    
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can(Permission::CAN_DELETE_REFUND_FORM);
    }
}
