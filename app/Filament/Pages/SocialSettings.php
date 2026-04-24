<?php

namespace App\Filament\Pages;

use App\Models\Option;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class SocialSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-share';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Social Publishing';
    protected static ?int $navigationSort = 10;
    protected static string $view = 'filament.pages.social-settings';
    protected static ?string $title = 'Social Media API Configuration';

    public ?string $fb_page_id = '';
    public ?string $ig_account_id = '';
    public ?string $fb_access_token = '';
    public bool $automatic_social_publish = false;

    public function mount(): void
    {
        $this->fb_page_id = Option::where('key', 'fb_page_id')->first()?->value ?? '';
        $this->ig_account_id = Option::where('key', 'ig_account_id')->first()?->value ?? '';
        $this->fb_access_token = Option::where('key', 'fb_access_token')->first()?->value ?? '';
        $this->automatic_social_publish = Option::where('key', 'automatic_social_publish')->first()?->value === '1';
    }

    public function testConnection(): void
    {
        if (!$this->fb_page_id || !$this->fb_access_token) {
            Notification::make()->title('Missing Credentials')->danger()->send();
            return;
        }

        try {
            $response = Http::get("https://graph.facebook.com/v19.0/{$this->fb_page_id}", [
                'fields' => 'name',
                'access_token' => $this->fb_access_token,
            ]);

            if ($response->successful()) {
                $name = $response->json('name');
                Notification::make()
                    ->title('Connection Successful!')
                    ->body("Successfully connected to Facebook Page: **{$name}**")
                    ->success()
                    ->send();
            } else {
                $error = $response->json('error.message') ?? 'Unknown API Error';
                Notification::make()
                    ->title('Connection Failed')
                    ->body("Facebook Error: {$error}")
                    ->danger()
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Connection Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function save(): void
    {
        Option::updateOrCreate(['key' => 'fb_page_id'], ['value' => $this->fb_page_id]);
        Option::updateOrCreate(['key' => 'ig_account_id'], ['value' => $this->ig_account_id]);
        Option::updateOrCreate(['key' => 'fb_access_token'], ['value' => $this->fb_access_token]);
        Option::updateOrCreate(['key' => 'automatic_social_publish'], ['value' => $this->automatic_social_publish ? '1' : '0']);

        Notification::make()
            ->title('Keys Saved Successfully')
            ->body('Your Facebook and Instagram publishing keys have been securely updated.')
            ->success()
            ->send();
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('test_connection')
                ->label('Test Connection')
                ->icon('heroicon-o-signal')
                ->color('warning')
                ->action(fn () => $this->testConnection()),
            Action::make('save_action')
                ->label('Save API Keys')
                ->icon('heroicon-o-check')
                ->color('primary')
                ->action(fn () => $this->save()),
        ];
    }
}
