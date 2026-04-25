<?php
namespace App\Filament\Resources\StaffResource\Pages;
use App\Filament\Resources\StaffResource;
use Filament\Resources\Pages\CreateRecord;
class CreateStaff extends CreateRecord {
    protected static string $resource = StaffResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['email_verified_at'] = now();
        return $data;
    }
}
