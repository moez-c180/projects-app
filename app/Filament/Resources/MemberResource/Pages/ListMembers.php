<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Filament\Resources\MemberResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Exception;
use App\Imports\MembersImport;

class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('importExcel')
                 ->action('openImportExcel')
                 ->form([
                    FileUpload::make('file')
                        ->disk('public')
                        ->required()
                        ->directory('members-import-sheets')
                        ->preserveFilenames()
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                 ]),
        ];
    }
    
    public function openImportExcel(array $data)
    {
        ini_set('max_execution_time', '3600'); 
        $filePath = Storage::disk('public')->path($data['file']);
        try {
            $import = Excel::import(new MembersImport(), $filePath);
            Notification::make()
                ->success()
                ->title('تم')
                ->body("تم تسجيل الأعضاء.")
                ->send();
        } catch (Exception $e) {
            Notification::make()
                ->danger()
                ->title('خطأ')
                ->body($e->getMessage())
                ->send();
        }
    }
}
