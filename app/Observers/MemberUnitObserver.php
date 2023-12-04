<?php

namespace App\Observers;

use App\Models\FinancialBranch;
use App\Models\MemberUnit;
use App\Models\Unit;
use App\Models\Member;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
        $member = Member::find($memberUnit->member_id);
        $member->unit_id = $memberUnit->unit_id;
        $member->financial_branch_id = $memberUnit->unit->financialBranch->id;
        $member->save();

        // $memberUnit->member()->update([
        //     'unit_id' => $memberUnit->unit_id,
        //     'financial_branch_id' => $memberUnit->unit->financialBranch->id,
        // ]);

        // Log::info(['unit_id' => $memberUnit->unit_id,
        // 'financial_branch_id' => $memberUnit->unit->financialBranch->id]);
    }

    /**
     * Handle the MemberUnit "deleted" event.
     *
     * @param  \App\Models\MemberUnit  $memberUnit
     * @return void
     */
    public function deleted(MemberUnit $memberUnit)
    {
        // $member = $memberUnit->member->refresh();
        $previousMemberUnit = $memberUnit->member->memberUnits()->latest()->first();
        if ($previousMemberUnit)
        {
            $unit = Unit::find($previousMemberUnit->unit_id);
            $unitId = $unit->id;
            $financialBranchId = $unit->financial_branch_id;
        } else {
            $unitId = $financialBranchId = null;
        }

        DB::table('members')
            ->where('id', $memberUnit->member_id)
            ->update([
                'unit_id' => $unitId,
                'financial_branch_id' => $financialBranchId,
            ]);
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
