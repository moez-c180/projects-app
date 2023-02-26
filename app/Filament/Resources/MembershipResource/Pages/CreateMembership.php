<?php

namespace App\Filament\Resources\MembershipResource\Pages;

use App\Filament\Resources\MembershipResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use App\Actions\CreateMembershipAction;

class CreateMembership extends CreateRecord
{
    protected static string $resource = MembershipResource::class;
    
    protected function handleRecordCreation(array $data): Model
    {
        return (new CreateMembershipAction($data, approved: true))->execute();
        // return static::getModel()::create($data);
    }
}
