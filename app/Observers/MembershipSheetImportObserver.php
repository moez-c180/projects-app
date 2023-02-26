<?php

namespace App\Observers;

use App\Models\MembershipSheetImport;

class MembershipSheetImportObserver
{
    /**
     * Handle the MembershipSheetImport "created" event.
     *
     * @param  \App\Models\MembershipSheetImport  $membershipSheetImport
     * @return void
     */
    public function created(MembershipSheetImport $membershipSheetImport)
    {
        //
    }

    /**
     * Handle the MembershipSheetImport "updated" event.
     *
     * @param  \App\Models\MembershipSheetImport  $membershipSheetImport
     * @return void
     */
    public function updated(MembershipSheetImport $membershipSheetImport)
    {
        //
    }

    /**
     * Handle the MembershipSheetImport "deleted" event.
     *
     * @param  \App\Models\MembershipSheetImport  $membershipSheetImport
     * @return void
     */
    public function deleted(MembershipSheetImport $membershipSheetImport)
    {
        $membershipSheetImport->memberships()->delete();
    }

    /**
     * Handle the MembershipSheetImport "restored" event.
     *
     * @param  \App\Models\MembershipSheetImport  $membershipSheetImport
     * @return void
     */
    public function restored(MembershipSheetImport $membershipSheetImport)
    {
        //
    }

    /**
     * Handle the MembershipSheetImport "force deleted" event.
     *
     * @param  \App\Models\MembershipSheetImport  $membershipSheetImport
     * @return void
     */
    public function forceDeleted(MembershipSheetImport $membershipSheetImport)
    {
        //
    }
}
