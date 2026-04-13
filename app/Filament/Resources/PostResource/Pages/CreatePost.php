<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\CreateRecord;

use App\Models\Media;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!empty($data['new_thumbnail_upload'])) {
            $path = $data['new_thumbnail_upload'];
            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            $name = basename($path);
            
            $mimetypes = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
            ];
            $mimetype = $mimetypes[$extension] ?? 'image/jpeg';

            $media = Media::create([
                'url' => $path,
                'path' => $path,
                'name' => $name,
                'extension' => $extension,
                'mimetype' => $mimetype,
                'alt' => $data['title'] ?? 'Thumbnail',
                'type' => 'image',
            ]);
            $data['thumbnail'] = $media->id;
        }
        unset($data['new_thumbnail_upload']);
        return $data;
    }
}
