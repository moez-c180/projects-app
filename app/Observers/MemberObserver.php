<?php

namespace App\Observers;

use App\Models\Member;

class MemberObserver
{
    /**
     * Handle the Member "created" event.
     *
     * @param  \App\Models\Member  $member
     * @return void
     */
    public function creating(Member $member)
    {
        if ($member->isDirty('pension_date'))
        {
            if (!is_null($member->pension_date))
            {
                $member->on_pension = 1;
            } else {
                $member->on_pension = 0;
            }
        }
    }

    /**
     * Handle the Member "updated" event.
     *
     * @param  \App\Models\Member  $member
     * @return void
     */
    public function updated(Member $member)
    {
        if ($member->isDirty('pension_date'))
        {
            if (!is_null($member->pension_date))
            {
                $member->on_pension = 1;
            } else {
                $member->on_pension = 0;
            }
        }
    }

    /**
     * Handle the Member "deleted" event.
     *
     * @param  \App\Models\Member  $member
     * @return void
     */
    public function deleted(Member $member)
    {
        //
    }

    /**
     * Handle the Member "restored" event.
     *
     * @param  \App\Models\Member  $member
     * @return void
     */
    public function restored(Member $member)
    {
        //
    }

    /**
     * Handle the Member "force deleted" event.
     *
     * @param  \App\Models\Member  $member
     * @return void
     */
    public function forceDeleted(Member $member)
    {
        //
    }
}
