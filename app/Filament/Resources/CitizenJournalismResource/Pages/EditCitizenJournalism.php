<?php
namespace App\Filament\Resources\CitizenJournalismResource\Pages;
use App\Filament\Resources\CitizenJournalismResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditCitizenJournalism extends EditRecord {
    protected static string $resource = CitizenJournalismResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
}
