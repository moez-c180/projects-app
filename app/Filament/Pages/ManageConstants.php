<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use Filament\Pages\SettingsPage;
use app\Settings\SystemConstantsSettings;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use App\Models\Unit;

class ManageConstants extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationGroup = 'البيانات الأساسية';
    protected static ?string $navigationLabel = 'قيم ثوابت المنظومة ';
    protected static ?int $navigationSort = 2;
    protected static ?string $label = 'قيم ثوابت المنظومة ';
    protected static string $settings = SystemConstantsSettings::class;

    protected function getFormSchema(): array
    {
        return [
            Card::make([
                TextInput::make('subscription_fees_co_in_service')
                    ->label('قيمة الاشتراك - عاملين بالخدمة')
                    ->numeric()->minValue(1)
                    ->required(),
                TextInput::make('subscription_fees_co_out_service')
                    ->label('قيمة الاشتراك - عاملين بالمعاش')
                    ->numeric()->minValue(1)
                    ->required(),
                TextInput::make('subscription_fees_nco_in_service')
                    ->label('قيمة الاشتراك - شرفيين بالخدمة')
                    ->numeric()->minValue(1)
                    ->required(),
                TextInput::make('subscription_fees_nco_out_service')
                    ->label('قيمة الاشتراك - شرفيين بالمعاش')
                    ->numeric()->minValue(1)
                    ->required(),  
            ])->columns(4),

            Card::make([
                TextInput::make('co_death')
                    ->label('وفاة العضو - عاملين')
                    ->numeric()->minValue(1)
                    ->required(),
                TextInput::make('co_relative_death')
                    ->label('وفاة أحد المعولين - عاملين')
                    ->numeric()->minValue(1)
                    ->required(),
                TextInput::make('co_marriage')
                    ->label('زواج العضو - عاملين')
                    ->numeric()->minValue(1)
                    ->required(),
                TextInput::make('co_relative_marriage')
                    ->label('زواج أحد المعولين - عاملين')
                    ->numeric()->minValue(1)
                    ->required(), 
                TextInput::make('co_age_honor_65')
                    ->label('تكريم السن ٦٥ - عاملين')
                    ->numeric()->minValue(1)
                    ->required(), 
                TextInput::make('co_age_honor_70')
                    ->label('تكريم السن ٧٠ - عاملين')
                    ->numeric()->minValue(1)
                    ->required(), 
                TextInput::make('co_grant')
                    ->label('منحة الزمالة - عاملين')
                    ->numeric()->minValue(1)
                    ->required(), 
                TextInput::make('co_funeral_fees')
                    ->label(' مصاريف جنازة - عاملين')
                    ->numeric()->minValue(1)
                    ->required(), 
            ])->columns(4),

            Card::make([
                TextInput::make('nco_death')
                    ->label('وفاة العضو - شرفيين')
                    ->numeric()->minValue(1)
                    ->required(),
                TextInput::make('nco_relative_death')
                    ->label('وفاة أحد المعولين - شرفيين')
                    ->numeric()->minValue(1)
                    ->required(),
                TextInput::make('nco_marriage')
                    ->label('زواج العضو - شرفيين')
                    ->numeric()->minValue(1)
                    ->required(),
                TextInput::make('nco_relative_marriage')
                    ->label('زواج أحد المعولين - شرفيين')
                    ->numeric()->minValue(1)
                    ->required(), 
                TextInput::make('nco_age_honor_65')
                    ->label('تكريم السن ٦٥ - شرفيين')
                    ->numeric()->minValue(1)
                    ->required(), 
                TextInput::make('nco_age_honor_70')
                    ->label('تكريم السن ٧٠ - شرفيين')
                    ->numeric()->minValue(1)
                    ->required(), 
                TextInput::make('nco_grant')
                    ->label('منحة الزمالة - شرفيين')
                    ->numeric()->minValue(1)
                    ->required(), 
                TextInput::make('nco_funeral_fees')
                    ->label(' مصاريف جنازة - شرفيين')
                    ->numeric()->minValue(1)
                    ->required(), 
            ])->columns(4),
            Card::make([
                Select::make('pension_unit_id')
                    ->label('وحدة المعاش')
                    ->options(Unit::all()->pluck('name', 'id'))
                    ->required()
            ])

        ];
    }
}
