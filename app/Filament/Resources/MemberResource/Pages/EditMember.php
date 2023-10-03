<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Filament\Resources\MemberResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Member;
use Filament\Notifications\Notification;

class EditMember extends EditRecord
{
    protected static string $resource = MemberResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function beforeSave(): void
    {
        $military_number = $this->data['military_number'];
        $pension_date = $this->data['pension_date'];
        if (is_null($pension_date))
        {
            $onPension = 0;
        } else {
            $onPension = 1;
        }
        
        $lookupMember = Member::where([
            'military_number' => $military_number,
            'on_pension' => $onPension,
        ])
        ->whereNot('id', $this->data['id'])->count();
        
        if($lookupMember !== 0)
        {
            Notification::make() 
                ->title('هناك عضو بنفس الرقم العسكري و حالة المعاش')
                ->danger()
                ->send();
            $this->halt();
        }
    }
}
