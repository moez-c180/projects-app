<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberResource\Pages;
use App\Filament\Resources\MemberResource\RelationManagers;
use App\Models\Member;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Models\Rank;
use Filament\Forms\Components\Toggle;
use App\Models\Category;
use App\Models\Department;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\Queue\Job;
use stdClass;
use App\Filament\Resources\MemberResource\RelationManagers\JobsRelationManager;
use App\Filament\Resources\MemberResource\RelationManagers\PromotionsRelationManager;
use Filament\Forms\Components\Card;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;
use Illuminate\Validation\Rule;
use App\Models\BankName;
use Closure;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Fieldset;
use App\Filament\Resources\MemberResource\RelationManagers\MemberUnitsRelationManager;
use App\Filament\Resources\MemberResource\RelationManagers\MemberJobsRelationManager;
use App\Models\Unit;
use App\Models\FinancialBranch;
use App\Models\MemberUnit;
use Filament\Tables\Filters\TernaryFilter;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use App\Models\Review;
use Illuminate\Database\Eloquent\Model;
use App\Models\Permission;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationLabel = 'الأعضاء ';
    protected static ?string $label = 'الأعضاء ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    Select::make('category_id')
                        ->label('الفئة')
                        ->options(Category::all()->pluck('name', 'id'))
                        ->reactive()
                        ->required(),
                    Select::make('rank_id')
                        ->label('الرتبة / الدرجة')
                        ->options(function(Closure $get) {
                            $categoryId = $get('category_id');
                            return Rank::whereHas('categories', function($query) use ($categoryId){
                                $query->where('category_id', $categoryId);
                            })->pluck('name', 'id');
                        })
                        ->required()
                        ->disabled(function(Closure $get) {
                            if ( is_null($get('id')))
                            {
                                return false;
                            }
                            $member = Member::findOrFail($get('id'));
                            if ($member->memberPromotions()->count() > 0)
                            {
                                return true;
                            }
                            return false;
                        })->visibleOn(['view']),
                    // Select::make('rank_id')
                    //     ->label('الرتبة / الدرجة')
                    //     ->options(Rank::all()->pluck('name', 'id')),
                    Toggle::make('is_general_staff')->label('أ ح'),
                    TextInput::make('name')->label('الاسم')->required()->maxLength(255),
                    
                    TextInput::make('military_number')
                        // ->rules(Rule::unique('members', 'military_number'))
                        ->label('الرقم العسكري')->maxLength(255),
                    TextInput::make('seniority_number')
                    // ->unique()
                        ->label('رقم الأقدمية')->maxLength(255),
                    
                    Select::make('department_id')
                        ->label('السلاح')
                        ->required()
                        ->options(Department::all()->pluck('name', 'id')),
                ])->columns(3),

                Card::make()->schema(
                    [Select::make('unit_id')
                        ->label('الوحدة')
                        ->options(Unit::all()->pluck('name', 'id'))
                        ->visibleOn(['view']),
                    Select::make('financial_branch_id')
                        ->label('الفرع المالي')
                        ->options(FinancialBranch::all()->pluck('name', 'id'))
                        ->visibleOn(['view']),
                ])->columns(1),
                Card::make()->schema([
                    DatePicker::make('graduation_date')->label('تاريخ التخرج'),
                    TextInput::make('class')->maxLength(255)->label('الدفعة'),
                    DatePicker::make('birth_date')->label('تاريخ الميلاد'),
                    TextInput::make('national_id_number')->label('الرقم القومي')->maxLength(14),
                    DatePicker::make('travel_date')->label('تاريخ السفر'),
                    DatePicker::make('return_date')->label('تاريخ العودة'),
                ])->columns(3),
                Card::make()->schema([
                    TextInput::make('address')->label('العنوان')->maxLength(255),
                    TextInput::make('mobile_phone_number')->label('رقم تليفون المحمول')->maxLength(255),
                    TextInput::make('home_phone_number')->label('رقم تليفون المنزل')->maxLength(255),
                    TextInput::make('beneficiary_name')->label('اسم المستفيد')->maxLength(255),
                    TextInput::make('beneficiary_title')->label('صفة المستفيد')->maxLength(255),
                    TextInput::make('beneficiary_phone_number')->label('رقم تليفون المستفيد')->maxLength(255),

                ])->columns(3),
                Card::make()->schema([
                    DatePicker::make('pension_date')->label('تاريخ الإحالة للمعاش'),
                    TextInput::make('pension_reason')->label('سبب الإحالة للمعاش'),
                    DatePicker::make('death_date')->label('تاريخ الوفاة'),
                ])->columns(3),
                Card::make()->schema([
                    TextInput::make('register_number')->label('رقم السجل'),
                    TextInput::make('file_number')->label('رقم الملف'),
                    Select::make('review')
                        ->options(
                            Review::all()->pluck('name', 'id'))
                        ->label('مراجعة'),
                    TextInput::make('wallet')->label('الرصيد')->hiddenOn(['create', 'edit'])->disabled(),
                    DatePicker::make('membership_start_date')
                        ->label('تاريخ الاشتراك')
                        ,
                    Toggle::make('membership_enabled')
                            ->label("تحصيل القسط"),
                    Textarea::make('notes')->label('ملاحظات'),
                ])->columns(3),
                
                Card::make()->schema([
                    TextInput::make('bank_account_number')->label('رقم حساب البنك')->maxLength(255),
                    Select::make('bank_name_id')
                        ->options(BankName::all()->pluck('name', 'id'))
                        ->label('البنك'),
                    TextInput::make('bank_branch_name')->label('اسم فرع البنك')->maxLength(255),
                ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')->getStateUsing(static function (stdClass $rowLoop): string {
                    return (string) $rowLoop->iteration;
                }),
                TextColumn::make('military_number')
                    ->label('الرقم العسكري')
                    ->sortable()
                    ->searchable(isIndividual: true, isGlobal: true)
                    ->toggleable(),
                TextColumn::make('seniority_number')
                    ->label('رقم الأقدمية')
                    ->sortable()
                    ->searchable(isIndividual: true, isGlobal: true)
                    ->toggleable(),
                TextColumn::make('rank.name')
                    ->sortable()
                    ->getStateUsing(function($record) {
                        return $record->getRankName();
                    })
                    ->label('الرتبة / الدرجة')
                    ->toggleable(),
                BooleanColumn::make('is_general_staff')->label('أ ح'),

                TextColumn::make('name')->label('الاسم')
                    ->sortable()
                    ->searchable(isIndividual: true, isGlobal: true, query: function (Builder $query, string $search): Builder {
                        return $query->search($search);
                    })
                    ->toggleable(),
                TextColumn::make('department.name')
                    ->sortable()
                    ->label('السلاح')
                    ->toggleable(),

                BooleanColumn::make('is_nco')
                    ->getStateUsing(function($record) {
                        return $record->category->is_nco;
                    })
                    ->label('شرفيين')
                    ->toggleable(),
                TextColumn::make('category.name')
                    ->sortable()
                    ->label('الفئة')
                    ->toggleable(),
                TextColumn::make('on_pension')
                    ->label('خدمة')
                    ->getStateUsing(function($record) {
                        return is_null($record->pension_date) ? 'خدمة' : 'معاش';
                    }),
                BooleanColumn::make('dead')
                    ->label('متوفي')
                    ->getStateUsing(function($record) {
                        return !is_null($record->death_date) ? true : false;
                    }),
                TextColumn::make('pension_date')
                    ->label('تاريخ الإحالة للمعاش')
                    ->toggleable(),
                TextColumn::make('pension_reason')
                    ->label('سبب الإحالة للمعاش')
                    ->toggleable(),
                TextColumn::make('unit.name')->label('الوحدة')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('unit.financialBranch.name')->label('الفرع المالي')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('member_unit_date')
                    ->label('تاريخ النقل')
                    ->getStateUsing(fn($record) => $record->memberUnits()->latest()->first()?->movement_date),
                TextColumn::make('member_job')
                    ->label('الوظيفة')
                    ->getStateUsing(fn($record) => $record->memberJobs()->latest()->first()?->position?->name),
                TextColumn::make('member_job_date')
                    ->label('تاريخ شغل الوظيفة')
                    ->getStateUsing(fn($record) => $record->memberJobs()->latest()->first()?->job_filled_date),
                TextColumn::make('promotion_date')
                    ->label('تاريخ الترقي')
                    ->getStateUsing(fn($record) => $record->memberPromotions()->latest()->first()?->promotion_date),
                TextColumn::make('address')
                    ->label('العنوان')
                    ->searchable(isIndividual: true, isGlobal: false)
                    ->toggleable(),
                TextColumn::make('home_phone_number')
                    ->label('رقم تليفون المنزل')
                    ->searchable(isIndividual: true, isGlobal: false)
                    ->toggleable(),
                TextColumn::make('mobile_phone_number')
                    ->label('رقم تليفون المحمول')
                    ->searchable(isIndividual: true, isGlobal: false)
                    ->toggleable(),
                TextColumn::make('register_number')
                    ->label('رقم السجل')
                    ->toggleable(),
                TextColumn::make('review')
                    ->label('مراجعة')
                    ->toggleable(),
                TextColumn::make('national_id_number')
                    ->label('الرقم القومي')
                    ->toggleable(),
                TextColumn::make('file_number')
                    ->label('رقم الملف')
                    ->toggleable(),
                
                // TextColumn::make('previous_unit')->label('الوحدة السابقة')
                //     ->getStateUsing(function($record) {
                //         $memberUnit = MemberUnit::where(['member_id' => $record->id])->first()?->unit?->name;
                //         return $memberUnit;
                //     })
                //     ->sortable()
                //     ->toggleable(),
                
                TextColumn::make('class')
                    ->sortable()
                    ->label('الدفعة')
                    ->toggleable(),
                
                TextColumn::make('membership_start_date')
                    ->label('تاريخ الاشتراك')
                    ->toggleable(),
                
                TextColumn::make('wallet')
                    ->label('الرصيد')
                    ->description('جم')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('تاريخ التسجيل')
                    ->dateTime('d-m-Y, H:i a')
                    ->tooltip(function(TextColumn $column): ?string {
                        $state = $column->getState();
                        return $state->since();
                    })
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('rank_id')
                    ->label('الرتبة')
                    ->options(Rank::all()->pluck('name', 'id'))
                    ->searchable()
                    ->multiple(),
                SelectFilter::make('department_id')
                    ->label('السلاح')
                    ->options(Department::all()->pluck('name', 'id'))
                    ->searchable()
                    ->multiple(),
                SelectFilter::make('category_id')
                    ->label('الفئة')
                    ->options(Category::all()->pluck('name', 'id'))
                    ->searchable()
                    ->multiple(),
                SelectFilter::make('unit_id')
                    ->label('الوحدة')
                    ->options(Unit::all()->pluck('name', 'id'))
                    ->searchable()
                    ->multiple(),
                SelectFilter::make('financial_branch_id')
                    ->label('الفرع المالي')
                    ->options(FinancialBranch::all()->pluck('name', 'id'))
                    ->searchable()
                    ->multiple(),
                SelectFilter::make('review_id')
                    ->label('المراجعة')
                    ->options(Review::all()->pluck('name', 'id'))
                    ->searchable()
                    ->multiple(),
                TernaryFilter::make('work_pension')
                    ->label('خدمة / معاش')
                    ->trueLabel('خدمة')
                    ->falseLabel('معاش')
                    ->queries(
                            true: fn (Builder $query) => $query->whereNull('pension_date'),
                            false: fn (Builder $query) => $query->whereNotNull('pension_date'),
                            blank: fn (Builder $query) => $query->withoutTrashed(),
                    ),
                TernaryFilter::make('nco_co')
                    ->label('عاملين / شرفيين')
                    ->trueLabel('شرفيين')
                    ->falseLabel('عاملين')
                    ->queries(
                            true: fn (Builder $query) => $query->whereHas('category', fn($query) => $query->whereIsNco(true)),
                            false: fn (Builder $query) => $query->whereHas('category', fn($query) => $query->whereIsNco(false)),
                            blank: fn (Builder $query) => $query->withoutTrashed(),
                    ),
                TernaryFilter::make('is_dead')
                    ->label('متوفي')
                    ->trueLabel('متوفي')
                    ->falseLabel('غير متوفي')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('death_date'),
                        false: fn (Builder $query) => $query->whereNull('death_date'),
                        blank: fn (Builder $query) => $query,
                    ),
                DateFilter::make('death_date')->label('تاريخ الوفاة'),
                DateFilter::make('created_at')->label('تاريخ التسجيل')

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                ExportAction::make('export')->exports([
                    ExcelExport::make()->fromTable()->except([
                        '#',
                    ]),                
                ])
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
    
    public static function getRelations(): array
    {
        return [
            MemberJobsRelationManager::class,
            PromotionsRelationManager::class,
            MemberUnitsRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'view' => Pages\ViewMember::route('/{record}'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }    


    public static function canViewAny(): bool
    {
        return auth()->user()->can(Permission::CAN_SEE_MEMBERS);
    }

    public static function canView(Model $record): bool
    {
        return auth()->user()->can(Permission::CAN_SEE_MEMBERS);
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can(Permission::CAN_CREATE_MEMBER);
    }

    public static function canEdit(Model $record): bool 
    {
        return auth()->user()->can(Permission::CAN_EDIT_MEMBER);
    }
    
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can(Permission::CAN_DELETE_MEMBER);
    }
}
