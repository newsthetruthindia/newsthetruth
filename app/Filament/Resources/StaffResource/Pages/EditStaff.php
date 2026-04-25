<?php
namespace App\Filament\Resources\StaffResource\Pages;
use App\Filament\Resources\StaffResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditStaff extends EditRecord {
    protected static string $resource = StaffResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Auto-verify if they aren't already (or if email changed)
        $data['email_verified_at'] = now();
        return $data;
    }

    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
}
