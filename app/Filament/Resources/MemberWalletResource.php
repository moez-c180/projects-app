<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberWalletResource\Pages;
use App\Filament\Resources\MemberWalletResource\RelationManagers;
use App\Models\MemberWallet;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\TextColumn;
use stdClass;
use Filament\Tables\Columns\BadgeColumn;
use App\Models\Permission;

class MemberWalletResource extends Resource
{
    protected static ?string $model = MemberWallet::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationLabel = 'الحركات المالية الخاصة بالأعضاء ';
    protected static ?string $label = 'الحركات المالية الخاصة بالأعضاء ';
    protected static ?string $navigationGroup = 'اشتراكات الأعضاء';
    protected static ?int $navigationSort = 3;

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
                TextColumn::make('member.name')->label('اسم العضو'),
                TextColumn::make('member.wallet')->label('الرصيد الحالي')->description('جم'),
                TextColumn::make('amount')->label('المبلغ')->description('جم'),
                BadgeColumn::make('type')->label('نوع العملية')
                    ->colors([
                        'primary',
                        'success' => static fn ($state): bool => $state === strtoupper(MemberWallet::TYPE_DEPOSIT),
                        'danger' => static fn ($state): bool => $state === strtoupper(MemberWallet::TYPE_WITHDRAW),
                    ])->getStateUsing(fn($record) => strtoupper($record->type)),
                // TextColumn::make('membership_sheet_import_id')->label('القصاصة'),
                TextColumn::make('membership_source')->label('القصاصة')->getStateUsing(function($record) {
                    if ($record->membershipSheetImport)
                    {
                        return $record->membershipSheetImport?->media->first()->file_name;
                    } else {
                        return "نقدي";
                    }
                }),
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
                
            ])
            ->bulkActions([
                
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
            'index' => Pages\ListMemberWallets::route('/'),
        ];
    }
    
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }
    
    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderByDesc('created_at')->orderByDesc('id');
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can(Permission::CAN_SEE_MEMBER_WALLET_TRANSACTIONS);
    }

    public static function canView(Model $record): bool
    {
        return auth()->user()->can(Permission::CAN_SEE_MEMBER_WALLET_TRANSACTIONS);
    }
}
