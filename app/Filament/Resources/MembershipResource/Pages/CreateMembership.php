<?php

namespace App\Filament\Resources\MembershipResource\Pages;

use App\Filament\Resources\MembershipResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use App\Actions\CreateMembershipAction;
use App\Models\Membership;

class CreateMembership extends CreateRecord
{
    protected static string $resource = MembershipResource::class;
    
    protected function handleRecordCreation(array $data): Model
    {
        (new CreateMembershipAction(
            data: $data, 
            approved: true,
        ))->execute();
        return Membership::latest()->first();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
