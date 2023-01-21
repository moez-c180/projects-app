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
                    Hidden::make('id'),
                    TextInput::make('military_number')
                        // ->rules(Rule::unique('members', 'military_number'))
                        ->label('الرقم العسكري')->maxLength(255),
                    TextInput::make('seniority_number')
                    // ->unique()
                        ->label('رقم الأقدمية')->maxLength(255),
                    Select::make('rank_id')
                        ->label('الرتبة / الدرجة')
                        ->options(Rank::all()->pluck('name', 'id'))
                        ->required()
                        ->disabled(function(Closure $get) {
                            if ( is_null($get('id')))
                            {
                                return false;
                            }
                            $member = Member::findOrFail($get('id'));
                            if ($member->promotions()->count() > 0)
                            {
                                return true;
                            }
                            return false;
                        }),
                    Select::make('category_id')
                        ->label('الفئة')
                        ->options(Category::all()->pluck('name', 'id'))
                        ->required(),
                    Toggle::make('is_general_staff')->label('أ ح'),
                    TextInput::make('name')->label('الاسم')->required()->maxLength(255),
                    TextInput::make('address')->label('العنوان')->maxLength(255),
                    TextInput::make('home_phone_number')->label('رقم تليفون المنزل')->maxLength(255),
                    TextInput::make('mobile_phone_number')->label('رقم تليفون المحمول')->maxLength(255),
                    TextInput::make('beneficiary_name')->label('اسم المستفيد')->maxLength(255),
                    TextInput::make('beneficiary_title')->label('صفة المستفيد')->maxLength(255),
                    TextInput::make('class')->maxLength(255)->label('الدفعة'),
                    Select::make('department_id')
                        ->label('السلاح')
                        ->required()
                        ->options(Department::all()->pluck('name', 'id')),
                    DatePicker::make('graduation_date')->label('تاريخ التخرج'),
                    DatePicker::make('birth_date')->label('تاريخ الميلاد'),
                    DatePicker::make('travel_date')->label('تاريخ السفر'),
                    DatePicker::make('return_date')->label('تاريخ العودة'),
                    TextInput::make('national_id_number')->label('الرقم القومي')->maxLength(14),
                    Select::make('bank_name_id')
                        ->options(BankName::all()->pluck('name', 'id'))
                        ->label('البنك'),
                    TextInput::make('bank_branch_name')->label('اسم فرع البنك')->maxLength(255),
                    TextInput::make('bank_account_number')->label('رقم حساب البنك')->maxLength(255),
                    DatePicker::make('pension_date')->label('تاريخ الإحالة للمعاش'),
                    Textarea::make('pension_reason')->label('سبب الإحالة للمعاش'),
                    DatePicker::make('death_date')->label('تاريخ الوفاة'),
                    TextInput::make('register_number')->label('رقم السجل'),
                    TextInput::make('file_number')->label('رقم الملف'),
                    TextInput::make('review')->label('مراجعة'),
                    Textarea::make('notes')->label('ملاحظات')
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
                TextColumn::make('military_number')->label('الرقم العسكري')->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('seniority_number')->label('رقم الأقدمية')->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('rank.name')
                ->getStateUsing(function($record) {
                    return $record->getRankName();
                })
                ->label('الرتبة / الدرجة'),
                TextColumn::make('name')->label('الاسم')->searchable(isIndividual: true, isGlobal: false),
                BooleanColumn::make('is_nco')
                    ->getStateUsing(function($record) {
                        return $record->category->is_nco;
                    })->label('شرفيين'),
                TextColumn::make('category.name')->label('الفئة'),
                TextColumn::make('class')->label('الدفعة'),
                TextColumn::make('department.name')->label('السلاح'),
                TextColumn::make('home_phone_number')->label('رقم تليفون المنزل')->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('mobile_phone_number')->label('رقم تليفون المحمول')->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('address')->label('العنوان')->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('created_at')->label('تاريخ التسجيل')->dateTime('d-m-Y, H:i a')
                    ->tooltip(function(TextColumn $column): ?string {
                        $state = $column->getState();
                        return $state->since();
                    })->sortable(),
            ])
            ->filters([
                SelectFilter::make('rank_id')
                    ->label('السلاح')
                    ->options(Rank::all()->pluck('name', 'id')),
                SelectFilter::make('department_id')
                    ->label('السلاح')
                    ->options(Department::all()->pluck('name', 'id')),
                SelectFilter::make('category_id')
                    ->label('الفئة')
                    ->options(Category::all()->pluck('name', 'id')),
                DateFilter::make('created_at')->label('تاريخ التسجيل')

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
            JobsRelationManager::class,
            PromotionsRelationManager::class,
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
}
