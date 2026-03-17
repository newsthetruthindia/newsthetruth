<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

use App\Models\Media;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!empty($data['new_thumbnail_upload'])) {
            $media = Media::create([
                'url' => $data['new_thumbnail_upload'],
                'alt' => $data['title'] ?? 'Thumbnail',
            ]);
            $data['thumbnail'] = $media->id;
        }
        unset($data['new_thumbnail_upload']);
        return $data;
    }
}
