<?php

namespace App\Filament\Resources\ParkDocumentResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ParkDocumentResource;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use App\Filament\Resources\ParkDocumentResource\Widgets\Accruals;



class ListParkDocuments extends ListRecords
{
    protected static string $resource = ParkDocumentResource::class;
    protected static ?string $title= 'UCR Park Document';

    use ExposesTableToWidgets;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Add UCR Park Document'),
        ];
    }
}
