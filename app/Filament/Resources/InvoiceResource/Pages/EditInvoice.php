<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;
    protected static ?string $title = 'Invoice';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Delete Selected')
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Active Invoice Deleted')
                        ->body($this->record->accruals->ucr_ref_id.' and '.$this->record->draftbills->draftbill_number.' has been selected successfully')
                        ->iconColor('success')
                        ->duration(5000)
                ),
            Action::make('Home')
                ->label('Return')
                ->icon('heroicon-o-inbox-stack')
                ->url(fn ($record) => InvoiceResource::getUrl('index')),
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
