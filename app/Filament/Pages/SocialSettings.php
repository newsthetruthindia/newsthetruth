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

    public function mount(): void
    {
        $this->fb_page_id = Option::where('key', 'fb_page_id')->first()?->value ?? '';
        $this->ig_account_id = Option::where('key', 'ig_account_id')->first()?->value ?? '';
        $this->fb_access_token = Option::where('key', 'fb_access_token')->first()?->value ?? '';
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save API Keys')
                ->icon('heroicon-o-check')
                ->color('primary')
                ->action(function () {
                    Option::updateOrCreate(['key' => 'fb_page_id'], ['value' => $this->fb_page_id]);
                    Option::updateOrCreate(['key' => 'ig_account_id'], ['value' => $this->ig_account_id]);
                    Option::updateOrCreate(['key' => 'fb_access_token'], ['value' => $this->fb_access_token]);

                    Notification::make()
                        ->title('Keys Saved Successfully')
                        ->body('Your Facebook and Instagram publishing keys have been securely updated.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
