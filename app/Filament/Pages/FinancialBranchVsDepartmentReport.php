<?php

namespace App\Filament\Pages;

use App\Exports\FinancialBranchVsDepartmentReportExport;
use App\Models\Department;
use App\Models\FinancialBranch;
use Filament\Pages\Page;
use App\Models\Member;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Filament\Pages\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use App\Actions\Reports\GetFinancialBranchVsDepartmentExportAction;


class FinancialBranchVsDepartmentReport extends Page
{
    // use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.financial-branch-vs-department-report';

    protected static ?string $navigationGroup = 'التقارير';

    protected static ?string $title = 'يومية عددية بالعاملين بالخدمة';

    protected static ?int $navigationSort = 2;
    public $data;
    public $isNco = true;
    public function __construct()
    {
        // $financialBranches = FinancialBranch::query()->get();
        // $firstRow[] = 'السلاح / الفرع المالي';
        // foreach($financialBranches as $financialBranch)
        // {
        //     $firstRow[] = $financialBranch->name;
        // }
        // $firstRow[] = 'الإجمالي';
        
        // $this->data = Department::query()->get()->map(function($department) use ($financialBranches) {
        //     $departmentRow = [
        //         'name' => $department->name,
        //     ];
        //     foreach($financialBranches as $financialBranch)
        //     {
        //         $departmentRow['fb-'.$financialBranch->id] = Member::query()
        //             ->whereDepartmentId($department->id)
        //             ->whereFinancialBranchId($financialBranch->id)->count();
        //     }
        //     $departmentRow['الإجمالي'] = $department->members()->count();
        //     return $departmentRow;
        // });
        // // Append totals of financial branches
        // $lastRow[] = 'الإجمالي';
        // foreach($financialBranches as $financialBranch)
        // {
        //     $lastRow[] = $financialBranch->members()->count();
        // }
        // $lastRow[] = 'x';
        // $this->data = $this->data->toArray();
        // $this->data[] = $lastRow;
        // array_unshift($this->data, $firstRow);
        $data = (new GetFinancialBranchVsDepartmentExportAction(isNco: $this->isNco))->execute();
        $this->data = $data;
    }
    // public function getTableRecords(): Collection | Paginator
    // {
    //     $financialBranches = FinancialBranch::query()->get();
    //     $data = Department::query()->get()->map(function($department) use ($financialBranches) {
    //         $departmentRow['x'] = [
    //             'name' => $department->name,
    //             'total' => $department->members()->count(),
    //         ];
    //         foreach($financialBranches as $financialBranch)
    //         {
    //             $departmentRow['y'][$financialBranch->name] = Member::whereFinancialBranchId($financialBranch->id)->count();
    //         }
    //         $departmentRow['y']['total'] = $department->members()->count();
    //         return $departmentRow;
    //     });
    //     return $data;
    // }

    public function getActions(): array
    {
        return [
            Action::make('export')
                ->action(function() {
                    return Excel::download(new FinancialBranchVsDepartmentReportExport, 'users.xlsx');
                })
            ];
    }
}
