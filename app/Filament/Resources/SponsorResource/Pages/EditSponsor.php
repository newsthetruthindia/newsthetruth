<?php

namespace App\Filament\Resources\SponsorResource\Pages;

use App\Filament\Resources\SponsorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSponsor extends EditRecord
{
    protected static string $resource = SponsorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!empty($data['new_image_upload'])) {
            $media = \App\Models\Media::create([
                'type' => 'image',
                'name' => $data['name'] . ' Ad',
                'url' => $data['new_image_upload'],
                'path' => $data['new_image_upload'],
                'mimetype' => 'image/' . pathinfo($data['new_image_upload'], PATHINFO_EXTENSION),
                'extension' => pathinfo($data['new_image_upload'], PATHINFO_EXTENSION),
            ]);
            
            $data['media_id'] = $media->id;
            $data['image_url'] = $data['new_image_upload'];
        }

        return $data;
    }
}
