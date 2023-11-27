<?php

namespace App\Filament\Resources\MemberResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Models\Unit;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Models\Position;

class MemberJobsRelationManager extends RelationManager
{
    protected static string $relationship = 'memberJobs';

    protected static ?string $recordTitleAttribute = 'current_job';
    protected static ?string $title = 'الوظائف ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('position_id')
                    ->options(Position::all()->pluck('name', 'id'))
                    ->label('الوظيفة')
                    ->searchable()
                    ->required(),
                DatePicker::make('job_filled_date')
                    ->label('تاريخ شغل الوظيفة')
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('position.name')->label('الوظيفة'),
                TextColumn::make('job_filled_date')->label('تاريخ شغل الوظيفة'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                // DeleteBulkAction::make(),
            ]);
    }    
}
