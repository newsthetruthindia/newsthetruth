<?php

namespace App\Filament\Pages;

use App\Models\Option;
use App\Services\SocialPublishingService;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class SocialSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-share';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Social Publishing';
    protected static ?int $navigationSort = 10;
    protected static string $view = 'filament.pages.social-settings';
    protected static ?string $title = 'Social Media API Configuration';

    // Existing API config
    public ?string $fb_page_id = '';
    public ?string $ig_account_id = '';
    public ?string $fb_access_token = '';
    public bool $automatic_social_publish = false;

    // New fields for token exchange
    public ?string $fb_app_id = '';
    public ?string $fb_app_secret = '';
    public ?string $short_lived_token = '';

    // Token status display
    public ?string $token_status = 'unknown';
    public ?string $token_page_name = '';

    public function mount(): void
    {
        $this->fb_page_id = Option::where('key', 'fb_page_id')->first()?->value ?? '';
        $this->ig_account_id = Option::where('key', 'ig_account_id')->first()?->value ?? '';
        $this->fb_access_token = Option::where('key', 'fb_page_access_token')->first()?->value
                              ?? Option::where('key', 'fb_access_token')->first()?->value ?? '';
        $this->automatic_social_publish = Option::where('key', 'automatic_social_publish')->first()?->value === '1';
        $this->fb_app_id = Option::where('key', 'fb_app_id')->first()?->value ?? '';
        $this->fb_app_secret = Option::where('key', 'fb_app_secret')->first()?->value ?? '';

        // Check token status on page load
        $this->checkTokenStatus();
    }

    public function checkTokenStatus(): void
    {
        $service = new SocialPublishingService();
        $result = $service->validateToken();

        if ($result['valid']) {
            $this->token_status = 'valid';
            $this->token_page_name = $result['page_name'] ?? '';
        } else {
            $this->token_status = 'invalid';
            $this->token_page_name = $result['error'] ?? 'Token validation failed';
        }
    }

    public function testConnection(): void
    {
        if (!$this->fb_page_id || !$this->fb_access_token) {
            Notification::make()->title('Missing Credentials')->body('Please fill in the Page ID and Access Token first.')->danger()->send();
            return;
        }

        try {
            $response = Http::get("https://graph.facebook.com/v21.0/{$this->fb_page_id}", [
                'fields' => 'name,fan_count',
                'access_token' => $this->fb_access_token,
            ]);

            if ($response->successful()) {
                $name = $response->json('name');
                $fans = number_format($response->json('fan_count', 0));
                Notification::make()
                    ->title('Connection Successful!')
                    ->body("Connected to: **{$name}** ({$fans} followers)")
                    ->success()
                    ->send();
                $this->token_status = 'valid';
                $this->token_page_name = $name;
            } else {
                $error = $response->json('error.message') ?? 'Unknown API Error';
                Notification::make()
                    ->title('Connection Failed')
                    ->body("Facebook Error: {$error}")
                    ->danger()
                    ->send();
                $this->token_status = 'invalid';
                $this->token_page_name = $error;
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Connection Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function exchangeToken(): void
    {
        if (!$this->short_lived_token) {
            Notification::make()->title('Missing Token')->body('Paste a short-lived user token from the Graph API Explorer.')->danger()->send();
            return;
        }

        if (!$this->fb_app_id || !$this->fb_app_secret) {
            Notification::make()->title('Missing App Credentials')->body('Enter your Facebook App ID and App Secret first.')->danger()->send();
            return;
        }

        if (!$this->fb_page_id) {
            Notification::make()->title('Missing Page ID')->body('Enter your Facebook Page ID first.')->danger()->send();
            return;
        }

        Notification::make()->title('Processing...')->body('Exchanging token. This may take a few seconds.')->info()->send();

        $service = new SocialPublishingService();
        $result = $service->exchangeForPageToken(
            $this->short_lived_token,
            $this->fb_app_id,
            $this->fb_app_secret,
            $this->fb_page_id
        );

        if ($result['success']) {
            $this->fb_access_token = $result['token'];
            $this->short_lived_token = ''; // Clear the input
            $this->token_status = 'valid';
            $this->token_page_name = $result['page_name'] ?? '';

            Notification::make()
                ->title('Token Exchange Successful!')
                ->body("Never-expiring page token generated for: **{$result['page_name']}**. Token has been saved automatically.")
                ->success()
                ->duration(10000)
                ->send();
        } else {
            Notification::make()
                ->title('Token Exchange Failed')
                ->body($result['error'] ?? 'Unknown error during token exchange.')
                ->danger()
                ->duration(10000)
                ->send();
        }
    }

    public function save(): void
    {
        Option::updateOrCreate(['key' => 'fb_page_id'], ['value' => $this->fb_page_id]);
        Option::updateOrCreate(['key' => 'ig_account_id'], ['value' => $this->ig_account_id]);
        Option::updateOrCreate(['key' => 'fb_app_id'], ['value' => $this->fb_app_id]);
        Option::updateOrCreate(['key' => 'fb_app_secret'], ['value' => $this->fb_app_secret]);
        Option::updateOrCreate(['key' => 'automatic_social_publish'], ['value' => $this->automatic_social_publish ? '1' : '0']);

        // If they manually entered a token, save it as the page access token
        if ($this->fb_access_token) {
            Option::updateOrCreate(['key' => 'fb_page_access_token'], ['value' => $this->fb_access_token]);
        }

        Notification::make()
            ->title('Settings Saved')
            ->body('Your social publishing configuration has been updated.')
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
                ->label('Save Settings')
                ->icon('heroicon-o-check')
                ->color('primary')
                ->action(fn () => $this->save()),
        ];
    }
}
