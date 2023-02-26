<?php

namespace App\Observers;

use App\Models\MemberWallet;
use App\Models\Member;

class MemberWalletObserver
{
    /**
     * Handle the MemberWallet "created" event.
     *
     * @param  \App\Models\MemberWallet  $memberWallet
     * @return void
     */
    public function created(MemberWallet $memberWallet)
    {
        if ($memberWallet->type == MemberWallet::TYPE_DEPOSIT)
        {
            $memberWallet->member->increment('wallet', $memberWallet->amount * 100);
            
        } else {
            $memberWallet->member->decrement('wallet', $memberWallet->amount * 100);
        }
    }

    /**
     * Handle the MemberWallet "updated" event.
     *
     * @param  \App\Models\MemberWallet  $memberWallet
     * @return void
     */
    public function updated(MemberWallet $memberWallet)
    {
        //
    }

    /**
     * Handle the MemberWallet "deleted" event.
     *
     * @param  \App\Models\MemberWallet  $memberWallet
     * @return void
     */
    public function deleted(MemberWallet $memberWallet)
    {
        //
    }

    /**
     * Handle the MemberWallet "restored" event.
     *
     * @param  \App\Models\MemberWallet  $memberWallet
     * @return void
     */
    public function restored(MemberWallet $memberWallet)
    {
        //
    }

    /**
     * Handle the MemberWallet "force deleted" event.
     *
     * @param  \App\Models\MemberWallet  $memberWallet
     * @return void
     */
    public function forceDeleted(MemberWallet $memberWallet)
    {
        //
    }
}
