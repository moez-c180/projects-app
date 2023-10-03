<?php
namespace App\Actions;

use App\Models\Member;
use App\Models\Membership;
use App\Models\MembershipOverAmount;
use App\Models\MemberWallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class AddMembershipAction
{
    public Member $member;
    public array $membershipArray;
    public array $memberUnpaidMonths;
    public array $membershipEntries = [];

    public $memberWallet;

    public function __construct(
        public readonly int $memberId,
        public readonly bool $approved = true,
        public readonly ?int $membershipSheetImportId = null,
        public int $membershipValue,
        public ?string $notes,
        public int $financialBranchId,
        public int $unitId,
        public float $paidAmount,
    ) {
        $this->member = Member::findOrFail($this->memberId);
        $this->memberUnpaidMonths = $this->member->getUnpaidMembershipMonths();
        $this->membershipValue = $this->getSubscriptionValue();
    }
    
    public function execute(): Model
    {
        $this->memberWallet = Member::find($this->member->id)->wallet;
        $this->populateMembershipArray();

        foreach($this->membershipEntries as $entry)
        {
            Membership::create($entry);
        }
        
        return Membership::query()->latest()->first() ?? MemberWallet::query()->latest()->first();
    }

    private function getSubscriptionValue(): int
    {
        return  $this->member->getSubscriptionValue();
    }

    private function populateMembershipArray()
    {
        // Iterate over unpaid months for member
        foreach($this->memberUnpaidMonths as $month)
        {
            // If the paid amount is not equal to zero and it's greater than
            if ($this->memberWallet !== 0 && $this->memberWallet >= $this->membershipValue)
            {
                $amount = $this->membershipValue;
                $this->membershipEntries[] = [
                    'approved' => $this->approved,
                    'member_id' => $this->member->id,
                    'membership_date' => $month,
                    'amount' => $amount,
                    'financial_branch_id' => $this->financialBranchId,
                    'unit_id' => $this->unitId,
                    'notes' => $this->notes ?? NULL,
                    'membership_value' => $this->membershipValue,
                    'paid_amount' => $this->paidAmount,
                    'membership_sheet_import_id' => $this->membershipSheetImportId
                ];
                $this->memberWallet -= $amount;
            }
        }
    }
}