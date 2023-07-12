<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithValidation;
use App\Models\Member;
use Exception;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\Importable;

class MembersImport implements ToModel, WithHeadingRow, SkipsEmptyRows, WithValidation, SkipsOnFailure
{
    use Importable;

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //
    }

    public function model(array $row)
    {
        $member = Member::create($row);
        // Check if promotion data is existing
        if (!empty($row['promotion_date']) and !empty($row['rank_id']) )
        {
            $member->promotions()->create([
                'rank_id' => $row['rank_id'],
                'promotion_date' => $row['promotion_date'],
            ]);
        }
        
        return $member;
    }


    public function startRow(): int
    {
        return 2;
    }

    public function rules(): array
    {
        return [
            'military_number' => ['required'], //, 'unique:members,military_number'
            'rank_id' => ['required', 'exists:ranks,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'is_general_staff' => ['in:0,1'],
            'name' => ['required'],
            'department_id' => ['required', 'exists:departments,id'],
            'graduation_date' => ['nullable', 'date_format:Y-m-d'],
            'birth_date' => ['nullable', 'date_format:Y-m-d'],
            'travel_date' => ['nullable', 'date_format:Y-m-d'],
            'national_id_number' => ['nullable', 'numeric'],
            'bank_account_number' => ['nullable'],
            'pension_date' => ['nullable', 'date_format:Y-m-d'],
            'death_date' => ['nullable', 'date_format:Y-m-d'],
            'bank_name_id' => ['nullable', 'exists:bank_names,id'],
            'membership_start_date' => ['nullable', 'date_format:Y-m-d'],
            'unit_id' => ['nullable', 'exists:units,id'],
            'financial_branch_id' => ['nullable', 'exists:financial_branches,id'],
            
        ];
    }

    public function onFailure(Failure ...$failures) 
    {
        dd($failures);
        foreach($failures as $failure)
        {
            // throw new ValidationException("Error with import row of members with military number: ".$failure->values()['military_number']." Attribute:".json_encode($failure->attribute()));
            // dd([
            //     $failure['row'],
            //     $failure['attribute'],
            //     $failure->['errors'],
            //     // $failure->values(),
            // ]);
            logger()->error($failure);
        }
    }

    public function isEmptyWhen(array $row): bool
    {
        return 
            empty($row['military_number']) &&
            empty($row['rank_id']) &&
            empty($row['category_id']) &&
            empty($row['name']) &&
            empty($row['department_id'])
            ;
    }
}
