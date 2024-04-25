<?php

namespace App\Filament\Resources\ParkDocumentResource\Pages;

use App\Filament\Resources\ParkDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateParkDocument extends CreateRecord
{
    protected static string $resource = ParkDocumentResource::class;
    protected static ?string $title = 'Create Park Document';
}
