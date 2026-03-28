<?php

namespace App\Filament\Pages;

use App\Models\Option;
use App\Models\User;
use App\Notifications\BroadcastNotification;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Notification as FacadesNotification;

class ManageNotifications extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-bell';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $title = 'Notifications';
    protected static string $view = 'filament.pages.manage-notifications';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'automatic_notifications' => Option::where('key', 'automatic_notifications')->first()?->value === '1',
        ]);
    }

    public function save(): void
    {
        // Settings are saved via afterStateUpdated, but we keep this
        // method to handle form submission (e.g. Enter key) without error.
        Notification::make()
            ->title('Settings up to date')
            ->success()
            ->send();
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Section::make('General Settings')
                    ->schema([
                        Toggle::make('automatic_notifications')
                            ->label('Automatic Article Broadcast')
                            ->helperText('When enabled, all subscribers will receive an email as soon as an article is published.')
                            ->live()
                            ->afterStateUpdated(fn ($state) => $this->saveSetting('automatic_notifications', $state ? '1' : '0')),
                    ]),

                Section::make('Manual YouTube Broadcast')
                    ->description('Send a YouTube video update to all subscribers instantly.')
                    ->schema([
                        TextInput::make('youtube_title')
                            ->label('Video Title')
                            ->placeholder('e.g. BREAKING: New Update on Kolkata Elections'),
                        TextInput::make('youtube_url')
                            ->label('YouTube URL')
                            ->url()
                            ->placeholder('https://www.youtube.com/watch?v=...'),
                    ])
                    ->footerActions([
                        \Filament\Forms\Components\Actions\Action::make('broadcast_youtube')
                            ->label('Broadcast to 368+ Subscribers')
                            ->color('danger')
                            ->requiresConfirmation()
                            ->action('sendYoutubeBroadcast'),
                    ]),
            ]);
    }

    protected function saveSetting($key, $value): void
    {
        Option::updateOrCreate(['key' => $key], ['value' => $value]);
        
        Notification::make()
            ->title('Settings Saved')
            ->success()
            ->send();
    }

    public function sendYoutubeBroadcast(): void
    {
        $title = $this->data['youtube_title'] ?? null;
        $url = $this->data['youtube_url'] ?? null;

        if (!$title || !$url) {
            Notification::make()
                ->title('Missing Information')
                ->body('Please provide both a Title and a YouTube URL.')
                ->danger()
                ->send();
            return;
        }

        $subscribers = User::where('type', 'user')->get();
        
        FacadesNotification::send($subscribers, new BroadcastNotification($title, $url));

        Notification::make()
            ->title('Broadcast Sent')
            ->body('Notification is being delivered to ' . $subscribers->count() . ' subscribers.')
            ->success()
            ->send();

        $this->form->fill([
            'automatic_notifications' => Option::where('key', 'automatic_notifications')->first()?->value === '1',
            'youtube_title' => null,
            'youtube_url' => null,
        ]);
    }
}
