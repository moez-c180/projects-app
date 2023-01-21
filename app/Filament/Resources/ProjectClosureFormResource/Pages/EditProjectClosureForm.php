<?php

namespace App\Filament\Resources\ProjectClosureFormResource\Pages;

use App\Filament\Resources\ProjectClosureFormResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProjectClosureForm extends EditRecord
{
    protected static string $resource = ProjectClosureFormResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
