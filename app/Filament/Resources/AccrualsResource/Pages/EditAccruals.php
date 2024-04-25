<?php

namespace App\Filament\Resources\AccrualsResource\Pages;

use App\Filament\Resources\AccrualsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAccruals extends EditRecord
{
    protected static string $resource = AccrualsResource::class;
    protected static ?string $title = 'Edit Accruals';
    protected static ?string $breadcrumb = 'Edit';
    protected static ?string $slugs = 'add';
    protected static ?string $recordTitleAttribute = 'ucr_ref_id';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
