<?php

namespace App\Filament\Resources\DraftbillResource\Pages;

use App\Filament\Resources\DraftbillResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDraftbill extends CreateRecord
{
    protected static string $resource = DraftbillResource::class;
    protected static ?string $title = 'Select UCR Reference ID';
    protected static ?string $breadcrumb = 'Select';
}

