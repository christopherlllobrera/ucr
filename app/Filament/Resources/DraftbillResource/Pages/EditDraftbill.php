<?php

namespace App\Filament\Resources\DraftbillResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\DraftbillResource;
use App\Models\draftbill;
use Actions\Save;
use Filament\Actions\Action;
use Filament\Forms\Components\Actions as ComponentsActions;
use PhpParser\Node\Expr\Cast\Bool_;

class EditDraftbill extends EditRecord
{
    protected static string $resource = DraftbillResource::class;
    protected static ?string $title = 'Draft Bill';
    protected static ?string $breadcrumb = 'Add Draft Bill';


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
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
