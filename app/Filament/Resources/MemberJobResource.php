<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberJobResource\Pages;
use App\Filament\Resources\MemberJobResource\RelationManagers;
use App\Models\MemberJob;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use stdClass;
use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;
use Filament\Tables\Filters\SelectFilter;
use App\Models\Position;

class MemberJobResource extends Resource
{
    protected static ?string $model = MemberJob::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationLabel = 'وظائف الأعضاء ';
    protected static ?string $label = 'وظائف الأعضاء ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')->getStateUsing(static function (stdClass $rowLoop): string {
                    return (string) $rowLoop->iteration;
                }),
                TextColumn::make('member.rank.name')->label('الرتبة'),
                TextColumn::make('member.seniority_number')->label('رقم الأقدمية'),
                TextColumn::make('member.military_number')->label('رقم العسكري'),
                TextColumn::make('member.name')->label('اسم العضو')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('position.name')->label('الوحدة')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('job_filled_date')->label('تاريخ شغل الوظيفة')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')->label('تاريخ التسجيل')->dateTime('d-m-Y, H:i a')
                    ->tooltip(function(TextColumn $column): ?string {
                        $state = $column->getState();
                        return $state->since();
                    })->sortable(),
            ])
            ->filters([
                DateFilter::make('job_filled_date')
                    ->label('تاريخ شغل الوظيفة'),
                SelectFilter::make('position_id')
                    ->label('الوظيفة')
                    ->options(Position::all()->pluck('name', 'id'))
                    ->searchable()
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
            'index' => Pages\ListMemberJobs::route('/'),
            'create' => Pages\CreateMemberJob::route('/create'),
            'view' => Pages\ViewMemberJob::route('/{record}'),
            'edit' => Pages\EditMemberJob::route('/{record}/edit'),
        ];
    }    

    public static function canCreate(): bool
    {
        return false;
    }
}
