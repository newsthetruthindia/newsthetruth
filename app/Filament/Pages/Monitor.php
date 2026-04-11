<?php

namespace App\Filament\Pages;

use App\Models\UserMonitor;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Monitor extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-tv';
    protected static ?string $navigationGroup = 'Control Center';
    protected static ?string $navigationLabel = 'News Monitor';
    protected static string $view = 'filament.pages.monitor';

    public ?array $youtube_urls = [];
    public ?array $rss_feeds = [];

    public function mount()
    {
        $monitor = UserMonitor::where('user_id', auth()->id())->first();
        
        // Initialize with empty strings if not found
        $this->youtube_urls = $monitor->youtube_urls ?? array_fill(0, 12, '');
        $this->rss_feeds = $monitor->rss_feeds ?? array_fill(0, 6, '');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('configure')
                ->label('Configure Monitor')
                ->icon('heroicon-o-cog-6-tooth')
                ->color('gray')
                ->form([
                    Repeater::make('youtube_urls')
                        ->label('Live YouTube Channels (Must be 12)')
                        ->schema([
                            TextInput::make('url')->label('YouTube URL')->url()->placeholder('https://www.youtube.com/watch?v=...'),
                        ])
                        ->minItems(12)
                        ->maxItems(12)
                        ->grid(3)
                        ->addable(false)
                        ->deletable(false),
                    
                    Repeater::make('rss_feeds')
                        ->label('RSS News Feeds (Must be 6)')
                        ->schema([
                            TextInput::make('url')->label('RSS Feed URL')->url()->placeholder('https://news.google.com/rss/...'),
                        ])
                        ->minItems(6)
                        ->maxItems(6)
                        ->grid(2)
                        ->addable(false)
                        ->deletable(false),
                ])
                ->fillForm(fn() => [
                    'youtube_urls' => collect($this->youtube_urls)->map(fn($u) => ['url' => $u])->toArray(),
                    'rss_feeds' => collect($this->rss_feeds)->map(fn($f) => ['url' => $f])->toArray(),
                ])
                ->action(function (array $data) {
                    $urls = collect($data['youtube_urls'])->pluck('url')->toArray();
                    $feeds = collect($data['rss_feeds'])->pluck('url')->toArray();

                    UserMonitor::updateOrCreate(
                        ['user_id' => auth()->id()],
                        ['youtube_urls' => $urls, 'rss_feeds' => $feeds]
                    );

                    $this->youtube_urls = $urls;
                    $this->rss_feeds = $feeds;

                    \Filament\Notifications\Notification::make()
                        ->title('Monitor Configuration Saved')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function getRssHeadlines(): array
    {
        $headlines = [];
        foreach ($this->rss_feeds as $feedUrl) {
            if (empty($feedUrl)) continue;

            $cacheKey = 'rss_monitor_' . md5($feedUrl);
            $feedHeadlines = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($feedUrl) {
                try {
                    $response = Http::timeout(5)->get($feedUrl);
                    if ($response->failed()) return [];
                    
                    $xml = simplexml_load_string($response->body());
                    if (!$xml) return [];
                    
                    $items = [];
                    $channelTitle = (string)($xml->channel->title ?? 'News');
                    
                    foreach (($xml->channel->item ?? []) as $item) {
                        $items[] = [
                            'title' => (string)$item->title,
                            'link' => (string)$item->link,
                            'source' => $channelTitle,
                        ];
                        if (count($items) >= 10) break;
                    }
                    return $items;
                } catch (\Exception $e) {
                    return [];
                }
            });

            $headlines = array_merge($headlines, $feedHeadlines);
        }

        return collect($headlines)->shuffle()->take(60)->toArray();
    }

    public function getYoutubeId($url): ?string
    {
        if (!$url) return null;
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $matches);
        return $matches[1] ?? null;
    }
}
