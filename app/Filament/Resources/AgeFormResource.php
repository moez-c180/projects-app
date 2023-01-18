<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgeFormResource\Pages;
use App\Filament\Resources\AgeFormResource\RelationManagers;
use App\Models\AgeForm;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;
use Filament\Tables\Columns\TextColumn;
use stdClass;
use Filament\Forms\Components\TextInput;
use App\Models\Member;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\SelectFilter;
use Closure;

class AgeFormResource extends Resource
{
    protected static ?string $model = AgeForm::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'المذكرات';
    protected static ?string $navigationLabel = 'مذكرة تكريم بلوغ السن  ';
    protected static ?string $label = 'مذكرة تكريم بلوغ السن ';
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('serial')
                    ->label('رقم المذكرة')->default(AgeForm::count() + 1)->required()->maxLength(255),
                DatePicker::make('form_date')
                    ->label('تاريخ المذكرة')->required()
                    ->default(now())
                    ->maxDate(now()),
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
                Select::make('age_form_type')
                    ->label('السن')
                    ->options(AgeForm::AGE_FORM_VALUES)
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (Closure $set, $state, $context, Closure $get) {
                        $member = Member::findOrFail($get('member_id'));
                        $amount = AgeForm::getAmount($state, $member->is_nco);
                        $set('amount', $amount);
                    }),
                TextInput::make('amount')
                        ->label('المبلغ')
                        ->numeric()
                        ->minValue(1)
                        ->required()
                        ->disabled(),
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
                TextColumn::make('age_form_type')->label('السن'),
                TextColumn::make('serial')->label('رقم المذكرة'),
                TextColumn::make('member.rank.name')
                    ->getStateUsing(fn($record) => $record->member->getRankName())
                    ->label('الرتبة'),
                TextColumn::make('member.name')->label('اسم العضو')
                    ->url(fn ($record) => url('/admin/members/'.$record->member->id.'/edit'), true),
                TextColumn::make('member.birth_date')->label('تاريخ الميلاد'),
                TextColumn::make('amount')->label('المبلغ'),
                TextColumn::make('form_date')->label('تاريخ المذكرة'),
                TextColumn::make('created_at')->label('تاريخ التسجيل')->dateTime('d-m-Y, H:i a')
                    ->tooltip(function(TextColumn $column): ?string {
                        $state = $column->getState();
                        return $state->since();
                    })->sortable(),
            ])
            ->filters([
                SelectFilter::make('age_form_type')
                    ->label('السن')
                    ->options(AgeForm::AGE_FORM_VALUES),
                DateFilter::make('created_at')->label('تاريخ التسجيل')

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListAgeForms::route('/'),
            'create' => Pages\CreateAgeForm::route('/create'),
            'view' => Pages\ViewAgeForm::route('/{record}'),
            'edit' => Pages\EditAgeForm::route('/{record}/edit'),
        ];
    }    
}
