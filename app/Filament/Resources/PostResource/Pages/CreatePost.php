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
        // Handle custom thumbnail upload if present
        if (!empty($data['new_thumbnail_upload'])) {
            $path = $data['new_thumbnail_upload'];
            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            $name = basename($path);

            // Map extension to mimetype
            $mimeMap = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'webp' => 'image/webp',
                'gif' => 'image/gif',
            ];
            $mimetype = $mimeMap[$extension] ?? 'image/' . $extension;

            // Create the Media record with ALL required fields
            try {
                $media = Media::create([
                    'type'      => 'image',
                    'path'      => $path,
                    'url'       => $path,
                    'name'      => $name,
                    'extension' => $extension,
                    'mimetype'  => $mimetype,
                    'alt'       => $data['title'] ?? 'Thumbnail',
                ]);

                if ($media) {
                    $data['thumbnail'] = $media->id;
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Media Creation Failed during Post Create: " . $e->getMessage());
                // We don't stop the process, but the post will have no thumbnail
            }
        }

        // Clean up the temporary form field
        unset($data['new_thumbnail_upload']);

        // Ensure user_id is set to a valid non-zero ID
        if (empty($data['user_id'])) {
            $data['user_id'] = auth()->id() ?: 1; // Default to ID 1 (Admin) if no auth session
        }

        return $data;
    }
}
