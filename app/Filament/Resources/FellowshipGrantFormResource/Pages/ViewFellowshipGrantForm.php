<?php

namespace App\Filament\Resources\FellowshipGrantFormResource\Pages;

use App\Filament\Resources\FellowshipGrantFormResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFellowshipGrantForm extends ViewRecord
{
    protected static string $resource = FellowshipGrantFormResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
