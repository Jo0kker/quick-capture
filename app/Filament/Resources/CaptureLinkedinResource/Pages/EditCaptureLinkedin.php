<?php

namespace App\Filament\Resources\CaptureLinkedinResource\Pages;

use App\Filament\Resources\CaptureLinkedinResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCaptureLinkedin extends EditRecord
{
    protected static string $resource = CaptureLinkedinResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
