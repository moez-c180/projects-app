<?php

namespace App\Filament\Resources\MemberUnitResource\Pages;

use App\Filament\Resources\MemberUnitResource;
use App\Models\FinancialBranch;
use App\Models\Position;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use App\Models\Unit;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Actions\Action;
use App\Models\Member;
use Filament\Notifications\Notification;

class ListMemberUnits extends ListRecords
{
    protected static string $resource = MemberUnitResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),

            Action::make('member_unit_job')
                ->label('تنقل العضو / الوظيفة')
                ->action(function(array $data) : void {
                    $member = Member::find($data['member_id']);
                    
                    $transaction = DB::transaction(function () use ($data, &$member) {    
                        $unit = Unit::find($data['unit_id']);
                        $member->memberJobs()->create([
                            'job_filled_date' => $data['job_filled_date'],
                            'position_id' => $data['position_id'],
                        ]);
                        $member->memberUnits()->create([
                            'unit_id' => $data['unit_id'],
                            'movement_date' => $data['movement_date'],
                        ]);
                        $member->update([
                            'unit_id' => $data['unit_id'],
                            'financial_branch_id' => $unit->financial_branch_id,
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
                            ->search($search)
                            ->limit(50)->pluck('name', 'id');
                        })->getOptionLabelUsing(fn ($value): ?string => Member::find($value)?->name)
                        ->required(),
                    Select::make('unit_id')
                        ->label('الوحدة')
                        ->preload()
                        ->options(Unit::all()->pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                    DatePicker::make('movement_date')
                        ->label('تاريخ النقل')
                        ->required(),
                    Select::make('position_id')
                        ->label('الوظيفة')
                        ->options(Position::all()->pluck('name', 'id'))
                        ->preload()
                        ->searchable()
                        ->required(),
                    DatePicker::make('job_filled_date')
                        ->label('تاريخ شغل الوظيفة')
                        ->required(),
                ])
        ];
    }
}
