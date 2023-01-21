<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MarriageFormResource\Pages;
use App\Filament\Resources\MarriageFormResource\RelationManagers;
use App\Models\MarriageForm;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use App\Models\Member;
use Closure;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use stdClass;
use Filament\Tables\Columns\BooleanColumn;
use Webbingbrasil\FilamentAdvancedFilter\Filters\BooleanFilter;
use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;
use Filament\Tables\Filters\SelectFilter;

class MarriageFormResource extends Resource
{
    protected static ?string $model = MarriageForm::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'المذكرات';
    protected static ?string $navigationLabel = 'مذكرة هدية الزواج  ';
    protected static ?string $label = 'مذكرة هدية الزواج ';
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('serial')
                    ->label('رقم المذكرة')->default(MarriageForm::count() + 1)->required()->maxLength(255),
                DatePicker::make('form_date')
                    ->label('تاريخ المذكرة')->required()
                    ->default(now())
                    ->maxDate(now()),
                DatePicker::make('marriage_date')
                    ->label('تاريخ الزواج')->required(),
                Select::make('member_id')
                        ->label(' العضو')
                        ->searchable()
                        ->getSearchResultsUsing(function(string $search) {
                            return Member::query()
                            ->whereLike('name', $search)
                            ->limit(50)->pluck('name', 'id');
                        })->getOptionLabelUsing(fn ($value): ?string => Member::find($value)?->name)
                        ->required()
                        ->reactive(),
                Select::make('is_relative')
                    ->label('نوع المذكرة')
                    ->options([
                        '0' => 'العضو نفسه',
                        '1' => 'احد الأقارب',
                    ])
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (Closure $set, $state, $context, Closure $get) {
                        $member = Member::findOrFail($get('member_id'));
                        $amount = MarriageForm::getAmount($state, $member->category->is_nco);
                        $set('amount', $amount);
                    })->visible(fn(Closure $get) => !is_null($get('member_id')) ),
                TextInput::make('relative_type')
                    ->label('درجة القرابة')
                    ->required()->visible(fn(Closure $get) => $get('is_relative') == true),
                TextInput::make('relative_name')->label('اسم المعول')->required()->visible(fn(Closure $get) => $get('is_relative') == true),
                TextInput::make('amount')
                        ->label('المبلغ')
                        ->numeric()
                        ->minValue(1)
                        ->required()
                        ->disabled()
                        ->visible(fn(Closure $get) => !is_null($get('is_relative')) ),
                Textarea::make('notes')->label('ملاحظات')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')->getStateUsing(static function (stdClass $rowLoop): string {
                    return (string) $rowLoop->iteration;
                }),
                BooleanColumn::make('is_relative')->label('أحد المعولين'),
                TextColumn::make('serial')->label('رقم المذكرة'),
                TextColumn::make('member.rank.name')
                    ->getStateUsing(fn($record) => $record->member->getRankName())
                    ->label('الرتبة'),
                TextColumn::make('member.name')->label('اسم العضو')
                    ->url(fn ($record) => url('/admin/members/'.$record->member->id.'/view'), true),
                TextColumn::make('marriage_date')->label('تاريخ الزواج'),
                TextColumn::make('amount')->label('المبلغ'),
                TextColumn::make('relative_type')->label('درجة القرابة'),
                TextColumn::make('relative_name')->label('اسم المعول'),
                TextColumn::make('form_date')->label('تاريخ المذكرة'),
                TextColumn::make('created_at')->label('تاريخ التسجيل')->dateTime('d-m-Y, H:i a')
                    ->tooltip(function(TextColumn $column): ?string {
                        $state = $column->getState();
                        return $state->since();
                    })->sortable(),
            ])
            ->filters([
                SelectFilter::make('is_relative')
                    ->label('أحد المعولين')
                    ->options([
                        0 => 'العضو نفسه',
                        1 => 'أحد المعولين',
                    ]),
                DateFilter::make('created_at')->label('تاريخ التسجيل')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListMarriageForms::route('/'),
            'create' => Pages\CreateMarriageForm::route('/create'),
            'view' => Pages\ViewMarriageForm::route('/{record}'),
            'edit' => Pages\EditMarriageForm::route('/{record}/edit'),
        ];
    }    
}
