<?php

namespace App\Filament\Resources\SpeakerResource\Pages;

use App\Filament\Resources\SpeakerResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Models\Speaker;

class ViewSpeaker extends ViewRecord
{
    protected static string $resource = SpeakerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
            ->slideOver()
            ->form(Speaker::getForm()),
            
        ];
    }
}
