<?php

namespace App\Filament\Resources\CollectionResource\Pages;

use App\Filament\Resources\CollectionResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditCollection extends EditRecord
{
    protected static string $resource = CollectionResource::class;
    protected static ?string $title = 'Collection';
    protected static ?string $breadcrumb = 'Add Collection';
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
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
