<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use RyanChandler\FilamentProfile\Pages\Profile as PagesProfile;

class Profile extends PagesProfile
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'الحساب';

    // protected static string $view = 'filament.pages.profile';
}
