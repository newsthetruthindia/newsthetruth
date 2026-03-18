<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Filament\Notifications\Notification;

class TwoFactorProfile extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';

    protected static string $view = 'filament.pages.two-factor-profile';
    
    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Two-Factor Auth';

    protected static ?string $title = 'Two-Factor Authentication';

    public $google2fa_secret;
    public $qr_code;
    public $secret;
    public $code;

    public function mount()
    {
        $user = Auth::user();
        if ($user->google2fa_secret) {
            $this->google2fa_secret = 'enabled';
        }
    }

    public function enable2fa()
    {
        $user = Auth::user();
        $google2fa = new Google2FA();
        $this->secret = $google2fa->generateSecretKey();

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $this->secret
        );

        $renderer = new ImageRenderer(
            new RendererStyle(256),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $this->qr_code = $writer->writeString($qrCodeUrl);
    }

    public function confirm2fa()
    {
        $user = Auth::user();
        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($this->secret, $this->code);

        if ($valid) {
            $user->google2fa_secret = $this->secret;
            $user->save();
            $this->google2fa_secret = 'enabled';
            $this->secret = null;
            $this->qr_code = null;
            
            Notification::make()
                ->title('Two-Factor Authentication Enabled')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Invalid verification code')
                ->danger()
                ->send();
        }
    }

    public function disable2fa()
    {
        $user = Auth::user();
        $user->google2fa_secret = null;
        $user->save();
        $this->google2fa_secret = null;
        
        Notification::make()
            ->title('Two-Factor Authentication Disabled')
            ->success()
            ->send();
    }
}
