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
