<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

use App\Models\Media;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

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
                        
                        // Refresh the page to show the new audio
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
