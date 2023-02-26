<?php
namespace App\Actions;

use App\Models\Member;
use App\Models\Membership;
use App\Models\MembershipOverAmount;

class CreateMembershipAction
{
    public Member $member;
    public array $membershipArray;
    public array $memberUnpaidMonths;
    public array $membershipEntries = [];
    public int $membershipValue;

    public function __construct(
        public readonly array $data,
        public readonly ?bool $approved = true,
        public readonly ?int $membershipSheetImportId = null
    ) {
        $this->member = Member::findOrFail($data['member_id']);
        $this->memberUnpaidMonths = $this->member->getUnpaidMembershipMonths();
        $this->populateMembershipArray();
        $this->membershipValue = $this->getSubscriptionValue();
    }
    
    public function execute(): Membership
    {
        foreach($this->membershipEntries as $entry)
        {
            Membership::create($entry);
        }
        return Membership::query()->latest()->first();
    }

    private function getSubscriptionValue(): int
    {
        return  $this->member->getSubscriptionValue();
    }

    private function populateMembershipArray()
    {
        $paidAmount = $this->data['paid_amount'];
        foreach($this->memberUnpaidMonths as $month)
        {
            if ($paidAmount != 0 && $paidAmount >= $this->data['membership_value'])
            {
                $amount = $this->data['membership_value'];
                $this->membershipEntries[] = [
                    'approved' => $this->approved,
                    'member_id' => $this->member->id,
                    'membership_date' => $month,
                    'amount' => $amount,
                    'financial_branch_id' => $this->data['financial_branch_id'],
                    'unit_id' => $this->data['unit_id'],
                    'notes' => $this->data['notes'] ?? NULL,
                    'membership_value' => $this->data['membership_value'],
                    'paid_amount' => $this->data['paid_amount'],
                    'membership_sheet_import_id' => $this->membershipSheetImportId
                ];
                $paidAmount -= $amount;

                
            }
            
        }

        if ($paidAmount != 0)
        {
            MembershipOverAmount::create([
                'member_id' => $this->member->id,
                'amount' => $paidAmount,
                'membership_sheet_import_id' => $this->membershipSheetImportId,
            ]);
        }
        
    }

    
    // public function getMembershipUnpaidMonths(): void
    // {
    //     $this->member->getMembershipMonths();
    // }
}