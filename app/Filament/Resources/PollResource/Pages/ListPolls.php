<?php

namespace App\Filament\Resources\PollResource\Pages;

use App\Filament\Resources\PollResource;
use Filament\Resources\Pages\ListRecords;

class ListPolls extends ListRecords
{
    protected static string $resource = PollResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
