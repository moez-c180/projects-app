<?php

namespace App\Imports;

use App\Actions\CreateMembershipAction;
use App\Models\FinancialBranch;
use App\Models\Membership;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use App\Models\Unit;
use App\Models\Member;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Database\Eloquent\Model;

class MembershipsImport implements ToModel, WithStartRow, WithValidation, SkipsEmptyRows, SkipsOnFailure
{

    public function __construct(
        public readonly string $membershipDate,
        public readonly int $membershipSheetImportId,
        public readonly bool $onPension
    ){}
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row): Model
    {
        $row['membership_date'] = $this->membershipDate;
        $row['paid_amount'] = $row[3];

        return (new CreateMembershipAction(
            data: $row,
            membershipSheetImportId: $this->membershipSheetImportId
        ))->execute();
    }
    
    public function collection(Collection $collection)
    {
        
        
        
    }

    public function startRow(): int
    {
        return 2;
    }

    public function rules(): array
    {
        return [];
        // financial_code	unit_code	miliraty_number	amount
        // return [
        //     0 => ['required', Rule::exists('financial_branches,id')],
        //     1 => ['required', Rule::exists('units,id')],
        //     2 => ['required', Rule::exists('members.id')],
        //     3 => ['required', 'numeric', 'gt:0'],
        // ];
    }

    public function prepareForValidation($data, $index)
    {
        $member = $this->getMember($data[2]);
        $data['financial_branch_id'] = $this->getFinancialBranchId($data[0]);
        $data['unit_id'] = $this->getUnitId($data[1]);
        $data['member_id'] = $member->id;
        $data['membership_value'] = $member->getSubscriptionValue();

        return $data;
    }

    private function getFinancialBranchId(string $financialBranchCode)
    {
        return FinancialBranch::whereCode($financialBranchCode)->first()?->id;
    }
    
    private function getUnitId(string $unitCode)
    {
        return Unit::whereCode($unitCode)->first()?->id;
    }
    
    private function getMemberId(string $militaryNumber)
    {
        return Member::whereMilitaryNumber($militaryNumber)->first()?->id;
    }
    
    private function getMember(string $militaryNumber)
    {
        return Member::whereMilitaryNumber($militaryNumber)
            ->whereOnPension($this->onPension)->first();
    }

    public function onFailure(Failure ...$failures)
    {
        dd($failures);
    }
}
