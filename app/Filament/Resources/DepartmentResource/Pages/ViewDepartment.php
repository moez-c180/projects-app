<?php

namespace App\Filament\Resources\DepartmentResource\Pages;

use App\Filament\Resources\DepartmentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Widgets\DepartmentWidget;

class ViewDepartment extends ViewRecord
{
    protected static string $resource = DepartmentResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            DepartmentWidget::class,
        ];
    }
}
