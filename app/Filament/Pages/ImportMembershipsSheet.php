<?php

namespace App\Filament\Pages;

use App\Imports\MembershipsImport;
use Filament\Forms\Components\FileUpload;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Livewire\WithFileUploads;
use Illuminate\Http\UploadedFile;
use Livewire\TemporaryUploadedFile;
use Maatwebsite\Excel\Facades\Excel;

class ImportMembershipsSheet extends Page implements HasForms
{
    use InteractsWithForms;
    use WithFileUploads;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.import-memberships-sheet';
    
    protected static ?string $navigationGroup = 'اشتراكات الأعضاء';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'رفع قصاصة';

    public $sheet;
    public $membership_date;

    protected function getFormSchema(): array 
    {
        return [
            FileUpload::make('sheet')
                ->directory('uploaded-sheets')
                ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                ->preserveFilenames()
                ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                    return (string) str($file->getClientOriginalName())->prepend(date('Y-m-d-H-m-s-'));
                })
                ->required(),
            DatePicker::make('membership_date')->required(),
        ];
    } 

    public function submit()
    {
        $data = $this->form->getState();
        $file = storage_path($data['sheet']);
        dd($file);
        Excel::import(new MembershipsImport, $file);
        
    }
}
