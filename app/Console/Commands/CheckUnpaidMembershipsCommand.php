<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Models\Membership;
use Illuminate\Console\Command;
use App\Actions\AddMembershipAction;

class CheckUnpaidMembershipsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'memberships:check-credit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Member::whereNotNull('wallet')->each(function($member){
            $membershipValue = $member->getSubscriptionValue();
            if ( $membershipValue >= $member->wallet)
            {
                (new AddMembershipAction(
                    memberId: $member->id,
                    approved: true,
                    membershipSheetImportId: null,
                    membershipValue: $membershipValue,
                    notes: null,
                    financialBranchId: $member->unit->financial_branch_id,
                    unitId: $member->unit->id,
                    paidAmount: $member->wallet,
                ))->execute();
            }
        });
        return Command::SUCCESS;
    }
}
