<?php

namespace App\Filament\Resources\AccrualsResource\Pages;

use App\Filament\Resources\AccrualsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAccruals extends ViewRecord
{
    protected static string $resource = AccrualsResource::class;

    protected function getHeaderActions(): array
    {
        return[
            Actions\EditAction::make()
            ->icon('heroicon-o-pencil-square')
            ->color('success'),
            Actions\Action::make('edit')
                    ->label('Add Park Doc')
                    ->icon('heroicon-o-document-text')
                    ->url(fn ($record) => AccrualsResource::getUrl('edit-parkdoc', ['record' => $record->id]))

        ];
    }
}
