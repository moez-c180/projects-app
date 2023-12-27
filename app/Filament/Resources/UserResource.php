<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Card;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TagsColumn;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'إدارة النظام';
    protected static ?int $navigationSort = 1;
    protected static ?string $label = 'مديري النظام ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('name')
                        ->label("الاسم")
                            ->required(),
                        TextInput::make('email')
                            ->required()
                            ->email()
                            ->label("البريد الالكتروني")
                            ->unique(table: static::$model, ignorable: fn ($record) => $record),
                        TextInput::make('password')
                            ->same('passwordConfirmation')
                            ->password()
                            ->maxLength(255)
                            ->label("كلمة السر")
                            ->required(fn ($component, $get, $livewire, $model, $record, $set, $state) => $record === null)
                            ->dehydrateStateUsing(fn ($state) => ! empty($state) ? Hash::make($state) : ''),
                        TextInput::make('passwordConfirmation')
                            ->label("تأكيد كلمة السر")
                            ->password()
                            ->dehydrated(false)
                            ->maxLength(255),
                        Select::make('roles')
                            ->multiple()
                            ->relationship('roles', 'name', function($query) {
                                return $query->whereNotIn('id', [777]);
                            })
                            ->preload(config('filament-authentication.preload_roles'))
                            ->label("Roles"),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->label("ID"),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label("Name"),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->label('Email'),
                TagsColumn::make('roles.name')
                    ->label("Roles"),
                TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i:s')
                    ->label("Created at"),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }    


    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyRole([Role::ROOT_ADMIN, Role::SUPER_ADMIN]);
    }
}
