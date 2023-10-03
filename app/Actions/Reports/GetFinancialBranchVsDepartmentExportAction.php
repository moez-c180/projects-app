<?php
namespace App\Actions\Reports;

use App\Models\FinancialBranch;
use App\Models\Department;
use App\Models\Member;

class  GetFinancialBranchVsDepartmentExportAction {

    public $data;
    
    public function __construct(public readonly bool $isNco)
    {
        
    }
    public function execute()
    {
        $financialBranches = FinancialBranch::query()->get();
        $firstRow[] = 'السلاح / الفرع المالي';
        foreach($financialBranches as $financialBranch)
        {
            $firstRow[] = $financialBranch->name;
        }
        $firstRow[] = 'الإجمالي';
        
        $this->data = Department::query()->get()->map(function($department) use ($financialBranches) {
            $departmentRow = [
                'name' => $department->name,
            ];
            foreach($financialBranches as $financialBranch)
            {
                $departmentRow['fb-'.$financialBranch->id] = Member::query()
                    ->whereDepartmentId($department->id)
                    ->whereFinancialBranchId($financialBranch->id)->count();
            }
            $departmentRow['الإجمالي'] = $department->members()->count();
            return $departmentRow;
        });
        // Append totals of financial branches
        $lastRow[] = 'الإجمالي';
        foreach($financialBranches as $financialBranch)
        {
            $lastRow[] = $financialBranch->members()->count();
        }
        $lastRow[] = '---';
        $this->data = $this->data->toArray();
        $this->data[] = $lastRow;
        array_unshift($this->data, $firstRow);
        return $this->data;
    }
}