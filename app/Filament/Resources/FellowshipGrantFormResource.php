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
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BooleanColumn;
use Closure;
use App\Models\Membership;
use Carbon\Carbon;
use Filament\Forms\Components\Fieldset;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;

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
                    DatePicker::make('calc_date')
                        ->label('تاريخ حساب المنحة')
                        ->visibleOn(['create']),
                    Select::make('member_id')
                        ->label(' العضو')
                        ->searchable()
                        ->getSearchResultsUsing(function(string $search) {
                            return Member::query()
                            ->search($search)
                            ->whereNull('pension_date')
                            ->whereNull('death_date')
                            ->limit(50)->pluck('name', 'id');
                        })
                        ->reactive()
                        ->getOptionLabelUsing(fn ($value): ?string => Member::find($value)?->name)
                        ->afterStateUpdated(function (Closure $set, $state, $context, Closure $get) {
                            $member = Member::findOrFail($get('member_id'));
                            if ($member->death_date && !$member->pension_date)
                            {
                                $set('grant_amount', $member->getFellowshipGrantValue());
                            } else {
                                $numberOfMembershipMonths = count($member->getTotalMembershipMonths(Carbon::parse($get('calc_date'))));
                                $fellowshipGrantValue = $member->getFellowshipGrantValue();
                                $grant_amount = ($numberOfMembershipMonths / 300) * $fellowshipGrantValue;
                                $set('grant_amount', ceil(($grant_amount * 10) / 10));
                            }
                        })
                        ->required(),
                    DatePicker::make('form_date')
                        ->label('تاريخ المذكرة')->required()
                        ->default(now())
                        ->maxDate(now()),
                    TextInput::make('grant_amount')
                        ->label('قيمة المنحة')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->disabled(),
                    ]),
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
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListFellowshipGrantForms::route('/'),
            'create' => Pages\CreateFellowshipGrantForm::route('/create'),
            'view' => Pages\ViewFellowshipGrantForm::route('/{record}'),
            'edit' => Pages\EditFellowshipGrantForm::route('/{record}/edit'),
        ];
    }    

    public static function canViewAny(): bool
    {
        return auth()->user()->can(Permission::CAN_SEE_FELLOWSHIP_GRANT_FORMS);
    }

    public static function canView(Model $record): bool
    {
        return auth()->user()->can(Permission::CAN_SEE_FELLOWSHIP_GRANT_FORMS);
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can(Permission::CAN_CREATE_FELLOWSHIP_GRANT_FORM);
    }

    public static function canEdit(Model $record): bool 
    {
        return auth()->user()->can(Permission::CAN_EDIT_FELLOWSHIP_GRANT_FORM);
    }
    
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can(Permission::CAN_DELETE_FELLOWSHIP_GRANT_FORM);
    }
}
