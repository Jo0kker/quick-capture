<?php

namespace App\Filament\Resources\CaptureLinkedinResource\Pages;

use App\Filament\Resources\CaptureLinkedinResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCaptureLinkedins extends ListRecords
{
    protected static string $resource = CaptureLinkedinResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
