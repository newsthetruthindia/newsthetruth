<?php

namespace App\Filament\Resources\VideoResource\Pages;

use App\Filament\Resources\VideoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVideos extends ListRecords
{
    protected static string $resource = VideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('syncYoutube')
                ->label('Sync from YouTube')
                ->icon('heroicon-o-arrow-path')
                ->color('success')
                ->action(function () {
                    $service = new \App\Services\YouTubeSyncService();
                    $count = $service->syncChannelVideos();
                    \Filament\Notifications\Notification::make()
                        ->title("Successfully synced {$count} new videos.")
                        ->success()
                        ->send();
                }),
            Actions\CreateAction::make(),
        ];
    }
}
