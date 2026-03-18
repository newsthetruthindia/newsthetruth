<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FA\Google2FA;

class Login extends BaseLogin
{
    /**
     * @return array<int | string, string | Form>
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getRememberFormComponent(),
                        TextInput::make('totp_code')
                            ->label('Authenticator Code (if 2FA enabled)')
                            ->numeric()
                            ->maxLength(6)
                            ->placeholder('123456'),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    public function authenticate(): ?LoginResponse
    {
        $data = $this->form->getState();

        $user = \App\Models\User::where('email', $data['email'])->first();

        // Check if user exists and has 2FA enabled
        if ($user && !empty($user->google2fa_secret)) {
            if (empty($data['totp_code'])) {
                throw ValidationException::withMessages([
                    'data.totp_code' => 'Please provide your 2FA authenticator code.',
                ]);
            }

            $google2fa = new Google2FA();
            $valid = $google2fa->verifyKey($user->google2fa_secret, $data['totp_code']);

            if (!$valid) {
                throw ValidationException::withMessages([
                    'data.totp_code' => 'The provided authentication code was invalid.',
                ]);
            }
        }

        return parent::authenticate();
    }
}
