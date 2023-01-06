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
use stdClass;

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
                TextInput::make('military_number')->maxLength(255),
                TextInput::make('seniority_number')->maxLength(255),
                Select::make('rank_id')
                    ->label('Rank')
                    ->options(Rank::all()->pluck('name', 'id'))
                    ->required(),
                Toggle::make('is_institute_graduate'),
                Toggle::make('is_nco'),
                Select::make('category_id')
                    ->label('Category')
                    ->options(Category::all()->pluck('name', 'id'))
                    ->required(),
                Toggle::make('is_general_staff'),
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('address')->maxLength(255),
                TextInput::make('home_phone_number')->maxLength(255),
                TextInput::make('mobile_phone_number')->maxLength(255),
                TextInput::make('beneficiary_name')->maxLength(255),
                TextInput::make('beneficiary_title')->maxLength(255),
                TextInput::make('class')->maxLength(255),
                Select::make('department_id')
                    ->label('Department')
                    ->options(Department::all()->pluck('name', 'id')),
                DatePicker::make('graduation_date'),
                DatePicker::make('birth_date'),
                DatePicker::make('travel_date'),
                DatePicker::make('return_date'),
                TextInput::make('national_id_number')->maxLength(14),
                TextInput::make('bank_account_number')->maxLength(255),
                DatePicker::make('pension_date'),
                Textarea::make('pension_reason'),
                DatePicker::make('death_date'),
                Textarea::make('notes')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')->getStateUsing(static function (stdClass $rowLoop): string {
                    return (string) $rowLoop->iteration;
                }),
                TextColumn::make('military_number'),
                TextColumn::make('seniority_number'),
                TextColumn::make('rank.name'),
                BooleanColumn::make('is_institute_graduate'),
                BooleanColumn::make('is_nco'),
                TextColumn::make('category.name'),
                BooleanColumn::make('is_general_staff'),
                TextColumn::make('name'),
                TextColumn::make('class'),
                TextColumn::make('department.name'),
                TextColumn::make('created_at')->dateTime('d-m-Y, H:i a')
                    ->tooltip(function(TextColumn $column): ?string {
                        $state = $column->getState();
                        return $state->since();
                    })->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }    
}
