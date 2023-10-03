<?php

namespace App\Filament\Resources\FellowshipGrantFormResource\Pages;

use App\Filament\Resources\FellowshipGrantFormResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFellowshipGrantForm extends CreateRecord
{
    protected static string $resource = FellowshipGrantFormResource::class;

    protected function afterCreate(): void
    {
        $this->record->member->update(['wallet' => 0]);
    }
}
