<?php

namespace App\Filament\Resources\DraftbillResource\Pages;

use App\Filament\Resources\DraftbillResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditDraftbill extends EditRecord
{
    protected static string $resource = DraftbillResource::class;
    protected static ?string $title = 'Draft Bill';
    protected static ?string $breadcrumb = 'Add Draft Bill';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Delete Selected')
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Active Draft Bill Deleted')
                        ->body($this->record->accrual->ucr_ref_id.' has been deleted successfully')
                        ->iconColor('success')
                        ->duration(5000)
                ),
            Action::make('Home')
                ->label('Return')
                ->icon('heroicon-o-wallet')
                ->url(fn ($record) => DraftbillResource::getUrl('index')),
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
