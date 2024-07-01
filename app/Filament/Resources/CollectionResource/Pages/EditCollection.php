<?php

namespace App\Filament\Resources\CollectionResource\Pages;

use App\Filament\Resources\CollectionResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditCollection extends EditRecord
{
    protected static string $resource = CollectionResource::class;
    protected static ?string $title = 'Collection';
    protected static ?string $breadcrumb = 'Add Collection';
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Delete Selected')
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Active Collection Deleted')
                        ->body($this->record->accruals->ucr_ref_id.','.$this->record->draftbills->draftbill_number.' and '.$this->record->invoices->accounting_document.' has been deleted successfully')
                        ->iconColor('success')
                        ->duration(5000)
                ),
            Action::make('Home')
                ->label('Return')
                ->icon('heroicon-o-credit-card')
                ->url(fn ($record) => CollectionResource::getUrl('index')),
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
