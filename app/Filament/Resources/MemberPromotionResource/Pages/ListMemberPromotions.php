<?php

namespace App\Filament\Resources\MemberPromotionResource\Pages;

use App\Filament\Resources\MemberPromotionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Actions\Action;
use Filament\Forms\Components\Select;
use App\Models\Member;
use App\Models\MemberPromotion;
use Filament\Forms\Components\DatePicker;
use App\Models\Rank;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use App\Models\Unit;
use app\Settings\SystemConstantsSettings;
use App\Models\FinancialBranch;

class ListMemberPromotions extends ListRecords
{
    protected static string $resource = MemberPromotionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('member_promotion_pension')
                ->label('ترقية عضو / إحالة للمعاش')
                ->action(function(array $data) : void {
                    // Check if there is a member already on pension
                    $member = Member::find($data['member_id']);
                    $militaryNumber = $member->military_number;
                    $lookupMember = Member::where([
                        'military_number' => $militaryNumber,
                        'on_pension' => 1,
                    ])->count();
                    
                    if($lookupMember !== 0)
                    {
                        Notification::make() 
                            ->title('هناك عضو بنفس الرقم العسكري و حالة المعاش')
                            ->danger()
                            ->send();
                        $this->halt();
                    }

                    // Check if unit pension is set
                    if (!Unit::find(app(SystemConstantsSettings::class)->pension_unit_id)) {
                        Notification::make() 
                            ->title('لم يتم تحديد وحدة المعاش')
                            ->danger()
                            ->send();
                        $this->halt();
                    }

                    $transaction = DB::transaction(function () use ($data, &$member) {    
                        $unit = Unit::find(app(SystemConstantsSettings::class)->pension_unit_id);
                        $member->memberPromotions()->create([
                            'rank_id' => $data['rank_id'],
                            'promotion_date' => $data['promotion_date'],
                        ]);
                        $member->memberUnits()->create([
                            'unit_id' => app(SystemConstantsSettings::class)->pension_unit_id,
                            'movement_date' => $data['promotion_date'],
                        ]);
                        $member->update([
                            'pension_date' => $data['pension_date'],
                            'pension_reason' => $data['pension_reason'],
                            'unit_id' => $unit->id,
                            'financial_branch_id' => $unit->financial_branch_id
                        ]);
                        return true;
                    });

                    if ($transaction)
                    {
                        Notification::make()
                            ->success()
                            ->title('تم')
                            ->body('تم إضافة')
                            ->send();
                    } else {
                        Notification::make()
                            ->danger()
                            ->title('خطأ')
                            ->body('حدث خطأ')
                            ->send();
                    }
                    
                })->form([
                    Select::make('member_id')
                        ->label('العضو')
                        ->searchable()
                        ->getSearchResultsUsing(function(string $search) {
                            return Member::query()
                            ->whereNull('pension_date')
                            ->where('on_pension', 0)
                            ->search($search)
                            ->limit(50)->pluck('name', 'id');
                        })->getOptionLabelUsing(fn ($value): ?string => Member::find($value)?->name)
                        ->required(),
                    Select::make('rank_id')
                        ->label('الرتبة الحالية')
                        ->options(Rank::all()->pluck('name', 'id'))
                        ->required(),
                    DatePicker::make('promotion_date')
                        ->label('تاريخ الترقي')
                        ->required(),
                    DatePicker::make('pension_date')
                        ->required()
                        ->label('تاريخ الإحالة للمعاش'),
                    TextInput::make('pension_reason')
                        ->required()
                        ->label('سبب الإحالة للمعاش'),
                ])
        ];
    }
}
