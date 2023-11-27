<?php
namespace App\Actions;

use App\Models\Member;
use App\Models\Membership;
use App\Models\MembershipOverAmount;
use App\Models\MemberWallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Actions\AddMembershipAction;

class CreateMembershipAction
{
    public Member $member;
    public $memberWallet;

    public function __construct(
        public readonly array $data,
        public readonly ?bool $approved = true,
        public readonly ?int $membershipSheetImportId = null
    ) {
        $this->member = Member::find($data['member_id']);
    }
    
    public function execute(): void
    {
        MemberWallet::create([
            'member_id' => $this->member->id,
            'amount' => $this->data['paid_amount'],
            'type' => MemberWallet::TYPE_DEPOSIT,
            'membership_sheet_import_id' => $this->membershipSheetImportId
        ]);
        (new AddMembershipAction(
            memberId: $this->member->id,
            approved: $this->approved,
            membershipSheetImportId: $this->membershipSheetImportId,
            membershipValue: $this->data['membership_value'],
            notes: $this->data['notes'] ?? null,
            financialBranchId: $this->data['financial_branch_id'],
            unitId: $this->data['unit_id'],
            paidAmount: $this->data['paid_amount'],
        ))->execute();
    }
}