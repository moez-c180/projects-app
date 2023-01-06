<?php

namespace App\Filament\Resources\IncomeCategoryResource\Pages;

use App\Filament\Resources\IncomeCategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIncomeCategory extends EditRecord
{
    protected static string $resource = IncomeCategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
