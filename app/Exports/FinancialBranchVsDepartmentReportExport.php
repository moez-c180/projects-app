<?php

namespace App\Exports;

use App\Actions\Reports\GetFinancialBranchVsDepartmentExportAction;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\FinancialBranch;
use App\Models\Department;
use App\Models\Member;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class FinancialBranchVsDepartmentReportExport implements FromArray, WithEvents
{
    public $data;
    
    
    public function __construct(
        public readonly bool $isNco,
        public readonly bool $onPension,
    )
    {
        
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
        $data = (new GetFinancialBranchVsDepartmentExportAction(isNco: $this->isNco, onPension: $this->onPension))->execute();
        return $data;
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getDelegate()->setRightToLeft(true);
            },
        ];
    }
}
