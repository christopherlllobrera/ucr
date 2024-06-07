<?php

namespace App\Filament\Resources\DraftbillResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\DraftbillResource;
use Filament\Notifications\Notification;

class EditDraftbill extends EditRecord
{
    protected static string $resource = DraftbillResource::class;
    protected static ?string $title = 'Draft Bill';
    protected static ?string $breadcrumb = 'Add Draft Bill';


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Active Draft Bill Deleted')
                        ->body( $this->record->accrual->ucr_ref_id . ' has been deleted successfully')
                        ->iconColor('success')
                        ->duration(5000),
                )
        ];
    }
    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()->hidden(),
            $this->getCancelFormAction()->hidden(),
        ];
    }
}
