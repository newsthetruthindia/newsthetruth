<x-filament-panels::page>
    <div class="space-y-6">
        @if ($google2fa_secret === 'enabled')
            <x-filament::card>
                <div class="prose dark:prose-invert">
                    <h3>Two-Factor Authentication is currently enabled</h3>
                    <p>Your account is protected with a Time-based One-Time Password (TOTP). You will be prompted to enter a code from your authenticator app when logging in.</p>
                </div>
                
                <div class="mt-4">
                    <x-filament::button color="danger" wire:click="disable2fa">
                        Disable 2FA
                    </x-filament::button>
                </div>
            </x-filament::card>
        @else
            @if (!$qr_code)
                <x-filament::card>
                    <div class="prose dark:prose-invert">
                        <h3>Enable Two-Factor Authentication</h3>
                        <p>Protect your account with an extra layer of security. We support Google Authenticator, Authy, and other standard TOTP apps.</p>
                    </div>
                    
                    <div class="mt-4">
                        <x-filament::button wire:click="enable2fa">
                            Set up 2FA
                        </x-filament::button>
                    </div>
                </x-filament::card>
            @else
                <x-filament::card>
                    <div class="prose dark:prose-invert">
                        <h3>Complete 2FA Setup</h3>
                        <p>1. Scan this QR code with your authenticator app.</p>
                        
                        <div class="mt-4 mb-4 bg-white p-4 inline-block rounded-lg">
                            {!! $qr_code !!}
                        </div>
                        
                        <p>Or manually enter this secret key: <strong>{{ $secret }}</strong></p>
                        
                        <p>2. Enter the code from your app below to confirm setup.</p>
                    </div>
                    
                    <div class="mt-4 max-w-sm flex items-center space-x-2">
                        <x-filament::input.wrapper>
                            <x-filament::input
                                type="text"
                                wire:model.defer="code"
                                placeholder="Enter 6-digit code"
                                class="w-full"
                            />
                        </x-filament::input.wrapper>
                        
                        <x-filament::button wire:click="confirm2fa">
                            Confirm
                        </x-filament::button>
                    </div>
                </x-filament::card>
            @endif
        @endif
    </div>
</x-filament-panels::page>
