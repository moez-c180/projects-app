<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MembershipSheetImportResource\Pages;
use App\Filament\Resources\MembershipSheetImportResource\RelationManagers;
use App\Jobs\ImportMembershipSheetJob;
use App\Models\MembershipSheetImport;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\TemporaryUploadedFile;
use Filament\Forms\Components\DatePicker;
use Carbon\Carbon;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use stdClass;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\DeleteAction;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MembershipsImport;
use Filament\Forms\Components\Toggle;

class MembershipSheetImportResource extends Resource
{
    protected static ?string $model = MembershipSheetImport::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'اشتراكات الأعضاء';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'رفع قصاصة';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                SpatieMediaLibraryFileUpload::make('sheet')
                    ->collection(MembershipSheetImport::MEDIA_COLLECTION_NAME)
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                    ->preserveFilenames()
                    ->enableDownload()
                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                        return (string) str($file->getClientOriginalName())->prepend(date('Y-m-d-H-m-s-'));
                    })
                    ->required(),
                DatePicker::make('membership_date')->default(Carbon::now()->startOfMonth())
                    ->rules(['required']),
                Toggle::make('on_pension')
                    ->label('معاش')
                    ->rules(['required']),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')->getStateUsing(static function (stdClass $rowLoop): string {
                    return (string) $rowLoop->iteration;
                }),
                TextColumn::make('user.name'),
                TextColumn::make('media.file_name')
                    ->url(fn($record) => $record->getFirstMediaUrl(MembershipSheetImport::MEDIA_COLLECTION_NAME)),
                BooleanColumn::make('processed')->label('تم معالجة القصاصة'),
                TextColumn::make('processing_start_time')->label('وقت بداية المعالجة'),
                TextColumn::make('processing_finish_time')->label('وقت انتهاء المعالجة'),
                TextColumn::make('created_at')->label('تاريخ التسجيل')->dateTime('d-m-Y, H:i a')
                    ->tooltip(function(TextColumn $column): ?string {
                        $state = $column->getState();
                        return $state->since();
                    })->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                DeleteAction::make()
                ->before(function (DeleteAction $action, $record) {
                    if (
                        $record->memberships()->count() !== 0 
                        ||
                        ( is_null($record->processing_finish_time) && !is_null($record->processing_start_time))
                    )
                    {
                        Notification::make()
                            ->danger()
                            ->title('لا يمكن الحذف')
                            ->body('لا يمكن حذف القصاصة حيث أنها تحتوي على سجلات اشتراكات.')
                            ->send();
                
                        $action->cancel();
                    }
                    // 

                    if ($record->membershipOverAmounts()->whereNotNull('refund_time')->count() !== 0)
                    {
                        Notification::make()
                            ->danger()
                            ->title('لا يمكن الحذف')
                            ->body('لا يمكن حذف القصاصة حيث أنها تحتوي على زيادات اشتراكات تم استرجاعها.')
                            ->send();
                
                        $action->cancel();
                    }
                })

                ->after(function($record) {
                    $record->clearMediaCollection(MembershipSheetImport::MEDIA_COLLECTION_NAME);
                }),
                Tables\Actions\Action::make('Process')->label('معالجة القصاصة')->action(function($record) {
                    ImportMembershipSheetJob::dispatch($record);
                })->visible(fn($record) => is_null($record->processing_finish_time)),
                Tables\Actions\Action::make('rollback')->label('التراجع عن القصاصة')
                    ->action(function(Tables\Actions\Action $action, $record) {
                        if ($record->membershipOverAmounts()->whereNotNull('refund_time')->count() !== 0)
                        {
                            Notification::make()
                                ->danger()
                                ->title('لا يمكن الحذف')
                                ->body('لا يمكن حذف القصاصة حيث أنها تحتوي على زيادات اشتراكات تم استرجاعها.')
                                ->send();
                    
                            $action->cancel();
                        }
                        $record->rollback();
                        Notification::make()
                                ->success()
                                ->title('تم')
                                ->body('تم التراجع عن سجلات الاشتراكات للقصاصة.')
                                ->send();
                    })->visible(fn($record) => !is_null($record->processing_finish_time) && !is_null($record->processing_start_time)),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])->defaultSort('created_at', 'desc');
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMembershipSheetImports::route('/'),
            'create' => Pages\CreateMembershipSheetImport::route('/create'),
            'view' => Pages\ViewMembershipSheetImport::route('/{record}'),
            'edit' => Pages\EditMembershipSheetImport::route('/{record}/edit'),
        ];
    }    
}
