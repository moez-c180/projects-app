<?php

namespace App\Observers;

use App\Models\RefundForm;
use App\Models\MemberWallet;

class RefundFormObserver
{
    /**
     * Handle the RefundForm "created" event.
     *
     * @param  \App\Models\RefundForm  $refundForm
     * @return void
     */
    public function created(RefundForm $refundForm)
    {
        MemberWallet::create([
            'member_id' => $refundForm->member_id,
            'amount' => $refundForm->amount,
            'type' => MemberWallet::TYPE_WITHDRAW
        ]);
    }

    /**
     * Handle the RefundForm "updated" event.
     *
     * @param  \App\Models\RefundForm  $refundForm
     * @return void
     */
    public function updated(RefundForm $refundForm)
    {
        //
    }

    /**
     * Handle the RefundForm "deleted" event.
     *
     * @param  \App\Models\RefundForm  $refundForm
     * @return void
     */
    public function deleted(RefundForm $refundForm)
    {
        //
    }

    /**
     * Handle the RefundForm "restored" event.
     *
     * @param  \App\Models\RefundForm  $refundForm
     * @return void
     */
    public function restored(RefundForm $refundForm)
    {
        //
    }

    /**
     * Handle the RefundForm "force deleted" event.
     *
     * @param  \App\Models\RefundForm  $refundForm
     * @return void
     */
    public function forceDeleted(RefundForm $refundForm)
    {
        //
    }
}
