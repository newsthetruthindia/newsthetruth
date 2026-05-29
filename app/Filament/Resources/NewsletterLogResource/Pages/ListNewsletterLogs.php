<?php

namespace App\Filament\Resources\NewsletterLogResource\Pages;

use App\Filament\Resources\NewsletterLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewsletterLogs extends ListRecords
{
    protected static string $resource = NewsletterLogResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
