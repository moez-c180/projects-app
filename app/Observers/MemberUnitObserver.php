<?php

namespace App\Observers;

use App\Models\FinancialBranch;
use App\Models\MemberUnit;
use App\Models\Unit;
use App\Models\Member;

class MemberUnitObserver
{
    /**
     * Handle the MemberUnit "created" event.
     *
     * @param  \App\Models\MemberUnit  $memberUnit
     * @return void
     */
    public function created(MemberUnit $memberUnit)
    {
        $memberUnit->member()->update([
            'unit_id' => $memberUnit->unit_id,
            'financial_branch_id' => $memberUnit->unit->financial_branch_id,
        ]);
    }

    /**
     * Handle the MemberUnit "updated" event.
     *
     * @param  \App\Models\MemberUnit  $memberUnit
     * @return void
     */
    public function updated(MemberUnit $memberUnit)
    {
        $memberUnit->member()->update([
            'unit_id' => $memberUnit->unit_id,
            'financial_branch_id' => $memberUnit->unit->financial_branch_id,
        ]);
    }

    /**
     * Handle the MemberUnit "deleted" event.
     *
     * @param  \App\Models\MemberUnit  $memberUnit
     * @return void
     */
    public function deleted(MemberUnit $memberUnit)
    {
        $member = $memberUnit->member;
        $previousMemberUnit = $memberUnit->member->memberUnits()->latest()->first();
        if ($previousMemberUnit)
        {
            $unit = Unit::find($previousMemberUnit->unit_id);
            $unitId = $unit->id;
            $financialBranchId = $unit->financial_branch_id;
        } else {
            $unitId = $financialBranchId = null;
        }

        $memberUnit->member()->update([
            'unit_id' => $unitId,
            'financial_branch_id' => $financialBranchId,
        ]);
        // Member::where([
        //     'id' => $memberUnit->member_id
        // ])->update([
            
        // ]);
    }

    /**
     * Handle the MemberUnit "restored" event.
     *
     * @param  \App\Models\MemberUnit  $memberUnit
     * @return void
     */
    public function restored(MemberUnit $memberUnit)
    {
        //
    }

    /**
     * Handle the MemberUnit "force deleted" event.
     *
     * @param  \App\Models\MemberUnit  $memberUnit
     * @return void
     */
    public function forceDeleted(MemberUnit $memberUnit)
    {
        //
    }
}
