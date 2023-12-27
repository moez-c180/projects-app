<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectClosureFormResource\Pages;
use App\Filament\Resources\ProjectClosureFormResource\RelationManagers;
use App\Models\ProjectClosureForm;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use stdClass;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Models\Member;
use Filament\Forms\Components\DatePicker;
use Closure;
use App\Models\ProjectClosureReason;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BooleanColumn;
use App\Models\Membership;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;

class ProjectClosureFormResource extends Resource
{
    protected static ?string $model = ProjectClosureForm::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'المذكرات';
    protected static ?string $navigationLabel = 'مذكرة تصفية المشروع  ';
    protected static ?string $label = 'مذكرة تصفية المشروع ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    TextInput::make('serial')
                        ->label('رقم المذكرة')->default(ProjectClosureForm::count() + 1)->required()->maxLength(255),
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
                            $member = Member::find($state);
                            $set('unit_name', $member->unit->name );
                            $set('total_subscription_payments', Membership::where('member_id', $state)->sum('amount') );
                            $set('total_forms_amount', $member->getMemberBenefitsAmount() );
                            $amount = $get('total_subscription_payments') - $get('total_forms_amount');
                            $set('amount', $amount);
                        }),
                    TextInput::make('unit_name')
                        ->visibleOn('create')
                        ->label('وحدة العضو')->disabled(),
                    TextInput::make('total_subscription_payments')
                        ->label('جملة المدفوعات')
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, $state, $context, Closure $get) {
                            $set('amount', ( $state - $get('total_forms_amount') ));
                        })
                        // ->disabled()
                        ,
                    TextInput::make('total_forms_amount')
                        ->label('جملة المزايا الإضافية')
                        ->required()
                        ->reactive()
                        ->disabled()
                        ->afterStateUpdated(function (Closure $set, $state, $context, Closure $get) {
                            $set('amount', ( $get('total_subscription_payments') - $state ));
                        })
                        // ->disabled()
                        ,
                    TextInput::make('amount')
                        ->label('قيمة المنحة')
                        ->numeric()
                        ->minValue(1)
                        ->required()
                        ->disabled(),
                    DatePicker::make('form_date')
                        ->label('تاريخ المذكرة')->required()
                        ->default(now())
                        ->maxDate(now()),
                    DatePicker::make('end_service_date')
                        ->label('تاريخ إنهاء الخدمة')->required()
                        ->default(now()),
                    Select::make('project_closure_reason_id')
                        ->required()
                        ->label('سبب إنهاء الخدمة')
                        ->options(ProjectClosureReason::all()->pluck('reason', 'id'))
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
                TextColumn::make('serial')->label('رقم المذكرة'),
                TextColumn::make('form_date')->label('تاريخ المذكرة'),
                TextColumn::make('member.rank.name')
                    ->getStateUsing(fn($record) => $record->member->getRankName())
                    ->label('الرتبة'),
                TextColumn::make('member.name')->label('اسم العضو')
                    ->url(fn ($record) => url('/admin/members/'.$record->member->id), true),
                TextColumn::make('member_unit_name')->label('اسم الوحدة')
                    ->getStateUsing(fn($record) => $record->member->unit->name),
                TextColumn::make('total_subscription_payments')
                    ->label('جملة المدفوعات')
                    ->description('جم'),
                TextColumn::make('total_forms_amount')
                    ->label('جملة المزايا الإضافية')
                    ->description('جم'),
                TextColumn::make('amount')
                    ->label('قيمة المنحة')
                    ->description('جم'),
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
            'index' => Pages\ListProjectClosureForms::route('/'),
            'create' => Pages\CreateProjectClosureForm::route('/create'),
            'view' => Pages\ViewProjectClosureForm::route('/{record}'),
            'edit' => Pages\EditProjectClosureForm::route('/{record}/edit'),
        ];
    }    

    public static function canViewAny(): bool
    {
        return auth()->user()->can(Permission::CAN_SEE_PROJECT_CLOSURE_FORMS);
    }

    public static function canView(Model $record): bool
    {
        return auth()->user()->can(Permission::CAN_SEE_PROJECT_CLOSURE_FORMS);
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can(Permission::CAN_CREATE_PROJECT_CLOSURE_FORM);
    }

    public static function canEdit(Model $record): bool 
    {
        return auth()->user()->can(Permission::CAN_EDIT_PROJECT_CLOSURE_FORM);
    }
    
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can(Permission::CAN_DELETE_PROJECT_CLOSURE_FORM);
    }
}
