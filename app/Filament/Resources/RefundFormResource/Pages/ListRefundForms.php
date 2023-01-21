<?php

namespace App\Filament\Resources\RefundFormResource\Pages;

use App\Filament\Resources\RefundFormResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRefundForms extends ListRecords
{
    protected static string $resource = RefundFormResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
