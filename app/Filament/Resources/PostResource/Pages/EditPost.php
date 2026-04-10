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
            Actions\Action::make('generate_audio')
                ->label('Generate Audio')
                ->icon('heroicon-o-speaker-wave')
                ->color('success')
                ->action(function (\App\Models\Post $record) {
                    try {
                        $controller = app(\App\Http\Controllers\PostController::class);
                        $req = new \Illuminate\Http\Request([
                            'text' => strip_tags($record->description),
                            'type' => 'text',
                            'post_id' => $record->id,
                        ]);
                        $controller->updatePostAudio($req);
                        \Filament\Notifications\Notification::make()
                            ->title('Audio Generated')
                            ->success()
                            ->send();

                        return redirect(request()->header('Referer'));
                    } catch (\Exception $e) {
                        \Filament\Notifications\Notification::make()
                            ->title('Generation Failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
                ->requiresConfirmation(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
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
                \Illuminate\Support\Facades\Log::error("Media Creation Failed during Post Edit: " . $e->getMessage());
                // We don't stop the process, but the post will have no thumbnail
            }
        }

        // Clean up the temporary form field
        unset($data['new_thumbnail_upload']);

        return $data;
    }
}
