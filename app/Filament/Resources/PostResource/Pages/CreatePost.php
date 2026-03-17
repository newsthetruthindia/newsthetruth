<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\CreateRecord;

use App\Models\Media;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
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
