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
        $currentMemberWallet = $memberWallet->member->wallet;
        if ($memberWallet->type == MemberWallet::TYPE_DEPOSIT)
        {
            $newWalletValue = $currentMemberWallet + $memberWallet->amount;
        } else {
            $newWalletValue = $currentMemberWallet - $memberWallet->amount;
        }
        $memberWallet->member()->update(['wallet' => $newWalletValue]);
    }
}
