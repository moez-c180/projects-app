<?php

namespace App\Observers;

use App\Models\Membership;
use App\Models\MemberWallet;
use Illuminate\Support\Facades\DB;

class MembershipObserver
{
    /**
     * Handle the Membership "created" event.
     *
     * @param  \App\Models\Membership  $membership
     * @return void
     */
    public function created(Membership $membership)
    {
        MemberWallet::create([
            'member_id' => $membership->member_id,
            'amount' => $membership->membership_value,
            'type' => MemberWallet::TYPE_WITHDRAW,
        ]);
    }

    /**
     * Handle the Membership "updated" event.
     *
     * @param  \App\Models\Membership  $membership
     * @return void
     */
    public function updated(Membership $membership)
    {
        //
    }

    /**
     * Handle the Membership "deleted" event.
     *
     * @param  \App\Models\Membership  $membership
     * @return void
     */
    public function deleted(Membership $membership)
    {
        //
    }

    /**
     * Handle the Membership "restored" event.
     *
     * @param  \App\Models\Membership  $membership
     * @return void
     */
    public function restored(Membership $membership)
    {
        //
    }

    /**
     * Handle the Membership "force deleted" event.
     *
     * @param  \App\Models\Membership  $membership
     * @return void
     */
    public function forceDeleted(Membership $membership)
    {
        //
    }
}
