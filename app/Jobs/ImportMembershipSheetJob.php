<?php

namespace App\Jobs;

use App\Imports\MembershipsImport;
use App\Models\MembershipSheetImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class ImportMembershipSheetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $membershipSheetImport;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(MembershipSheetImport $membershipSheetImport)
    {
        $this->membershipSheetImport = $membershipSheetImport;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $file = $this->membershipSheetImport->getFirstMediaPath(MembershipSheetImport::MEDIA_COLLECTION_NAME);
        $this->membershipSheetImport->update(['processing_start_time' => now()]);
        
        $this->membershipSheetImport->update([
            'processing_finish_time' => NULL,
            'processed' => false
        ]);

        Excel::import(new MembershipsImport(
            membershipDate: $this->membershipSheetImport->membership_date, 
            membershipSheetImportId: $this->membershipSheetImport->id
        ), $file);
        
        $this->membershipSheetImport->update([
            'processing_finish_time' => now(),
            'processed' => true
        ]);
    }
}
