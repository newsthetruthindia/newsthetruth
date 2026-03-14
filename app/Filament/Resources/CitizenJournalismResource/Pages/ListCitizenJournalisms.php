<?php
namespace App\Filament\Resources\CitizenJournalismResource\Pages;
use App\Filament\Resources\CitizenJournalismResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListCitizenJournalisms extends ListRecords {
    protected static string $resource = CitizenJournalismResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
