<?php

namespace App\Filament\Resources\DraftbillResource\Pages;

use Filament\Actions;
use Illuminate\Support\Str;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\DraftbillResource;
use App\Models\draftbill;

class EditDraftbill extends EditRecord
{
    protected static string $resource = DraftbillResource::class;
    protected static ?string $title = 'Create Draft Bill';
    protected static ?string $breadcrumb = 'Create';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    function generateSlug(string $title): string
    {
        $slug = Str::slug($title); // Use Laravel's Str::slug helper

        // Additional checks and modifications (e.g., ensure uniqueness)
        $model = draftbill::where('slug', $slug)->first();
        if ($model) {
            $slug = $slug . '-'. uniqid('', true);
        }

        return $slug;
    }


}
