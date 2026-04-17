<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StaffResource\Pages;
use App\Models\User;
use App\Models\Media;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StaffResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Staff Members';

    protected static ?string $pluralModelLabel = 'Staff Members';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('User Information')->schema([
                TextInput::make('firstname')->required()->maxLength(255),
                TextInput::make('lastname')->required()->maxLength(255),
                TextInput::make('email')->email()->required()->unique(User::class, 'email', ignoreRecord: true),
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->label(fn (string $context): string => $context === 'create' ? 'Password' : 'New Password (leave blank to keep)'),

                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->label('Roles'),
            ])->columns(2),

            Section::make('Reporter Profile')
                ->relationship('details')
                ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                    if (!empty($data['new_avatar_upload'])) {
                        $path = is_array($data['new_avatar_upload']) ? reset($data['new_avatar_upload']) : $data['new_avatar_upload'];
                        if ($path) {
                            $extension = pathinfo($path, PATHINFO_EXTENSION) ?: 'jpg';
                            $mimetype = 'image/' . ($extension === 'png' ? 'png' : ($extension === 'webp' ? 'webp' : 'jpeg'));
                            $media = \App\Models\Media::create([
                                'url' => $path,
                                'path' => $path,
                                'name' => basename($path),
                                'extension' => $extension,
                                'mimetype' => $mimetype,
                                'type' => 'image',
                            ]);
                            $data['attachment_id'] = $media->id;
                        }
                    }
                    unset($data['new_avatar_upload']);
                    return $data;
                })
                ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                    if (!empty($data['new_avatar_upload'])) {
                        $path = is_array($data['new_avatar_upload']) ? reset($data['new_avatar_upload']) : $data['new_avatar_upload'];
                        if ($path) {
                            $extension = pathinfo($path, PATHINFO_EXTENSION) ?: 'jpg';
                            $mimetype = 'image/' . ($extension === 'png' ? 'png' : ($extension === 'webp' ? 'webp' : 'jpeg'));
                            $media = \App\Models\Media::create([
                                'url' => $path,
                                'path' => $path,
                                'name' => basename($path),
                                'extension' => $extension,
                                'mimetype' => $mimetype,
                                'type' => 'image',
                            ]);
                            $data['attachment_id'] = $media->id;
                        }
                    }
                    unset($data['new_avatar_upload']);
                    return $data;
                })
                ->schema([
                    TextInput::make('designation')
                        ->placeholder('e.g. Senior Investigative Reporter')
                        ->maxLength(255),
                    
                    Forms\Components\Textarea::make('bio')
                        ->rows(4)
                        ->placeholder('Write a short bio for the reporter...')
                        ->columnSpanFull(),

                    Section::make('Social Media Links')
                        ->schema([
                            TextInput::make('twitter')
                                ->label('X (Twitter) URL')
                                ->url()
                                ->prefix('https://'),
                            TextInput::make('facebook')
                                ->label('Facebook URL')
                                ->url()
                                ->prefix('https://'),
                            TextInput::make('instagram')
                                ->label('Instagram URL')
                                ->url()
                                ->prefix('https://'),
                            TextInput::make('linkedin')
                                ->label('LinkedIn URL')
                                ->url()
                                ->prefix('https://'),
                        ])->columns(2),

                    Section::make('Profile Picture')
                        ->description('Upload or select the official photo for this staff member.')
                        ->icon('heroicon-m-user-circle')
                        ->schema([
                            FileUpload::make('new_avatar_upload')
                                ->label('Upload Status Photo')
                                ->image()
                                ->avatar()
                                ->disk('webapp_public')
                                ->directory('uploads/avatars')
                                ->imagePreviewHeight('250')
                                ->afterStateHydrated(fn ($component, $record) => $component->state($record?->media?->url ? [ltrim($record->media->url, '/')] : []))
                                ->columnSpanFull(),

                            Select::make('attachment_id')
                                ->label('Or Link to Existing Media')
                                ->relationship('media', 'url')
                                ->searchable()
                                ->placeholder('Search by image URL...')
                                ->columnSpanFull(),
                        ])->columns(2),
                ])->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->whereIn('type', ['admin', 'employee']))
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    ImageColumn::make('photo')
                        ->label('Photo')
                        ->circular()
                        ->height(90)
                        ->width(90)
                        ->state(fn ($record) => $record->details?->media?->url ? asset('storage/' . ltrim($record->details->media->url, '/')) : null)
                        ->extraImgAttributes(['class' => 'object-cover shadow-lg border-2 border-primary-500/50'])
                        ->extraAttributes(['class' => 'mb-4 mt-2 flex justify-center w-full'])
                        ->placeholder('No Image'),

                    Tables\Columns\Layout\Stack::make([
                        TextColumn::make('name')
                            ->label('Full Name')
                            ->weight('bold')
                            ->size('lg')
                            ->searchable(['firstname', 'lastname']),

                        TextColumn::make('email')
                            ->size('sm')
                            ->color('gray')
                            ->icon('heroicon-m-envelope')
                            ->searchable(),

                        Tables\Columns\Layout\Split::make([
                            TextColumn::make('email_verified_at')
                                ->label('Status')
                                ->getStateUsing(fn ($record) => $record->email_verified_at ? 'Verified' : 'Pending')
                                ->badge()
                                ->color(fn ($state) => $state === 'Verified' ? 'success' : 'warning'),

                            TextColumn::make('roles.name')
                                ->badge()
                                ->label('Role'),
                        ])->extraAttributes(['class' => 'mt-4']),

                        Tables\Columns\Layout\Split::make([
                            TextColumn::make('posts_count')
                                ->counts('posts')
                                ->label('Total Articles')
                                ->icon('heroicon-m-document-text')
                                ->size('xs'),

                            TextColumn::make('today_posts_count')
                                ->counts('posts', fn ($query) => $query->whereDate('created_at', now()))
                                ->label('Today')
                                ->badge()
                                ->color('success'),
                        ])->extraAttributes(['class' => 'mt-4 pt-4 border-t border-white/5']),
                    ])->extraAttributes(['class' => 'flex-1']),
                ])->extraAttributes([
                    'class' => 'p-6 bg-gray-900/50 rounded-xl border border-white/5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col items-center text-center',
                ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('sendVerification')
                        ->label('Verify Mail')
                        ->icon('heroicon-o-envelope')
                        ->color('info')
                        ->requiresConfirmation()
                        ->action(function (User $record) {
                            $token = Str::random(60);
                            DB::table('email_verifications')->updateOrInsert(
                                ['email' => $record->email],
                                ['token' => Hash::make($token), 'created_at' => now()]
                            );
                            $verifyUrl = env('FRONTEND_URL', 'https://newsthetruth.com') . '/verify-email?token=' . $token . '&email=' . urlencode($record->email);
                            try {
                                Mail::send([], [], function ($message) use ($record, $verifyUrl) {
                                    $message->to($record->email)->subject('Verify Your NTT Email')->html("
                                        <div style='font-family:Arial,sans-serif;max-width:600px;margin:0 auto;padding:40px 20px;'>
                                            <h1 style='font-size:28px;font-weight:900;color:#111827;margin-bottom:8px;'>News The Truth</h1>
                                            <hr style='border:none;border-top:3px solid #8c0000;margin:16px 0 32px;width:60px;'>
                                            <h2 style='font-size:20px;color:#111827;margin-bottom:16px;'>Email Verification</h2>
                                            <p style='color:#4b5563;font-size:15px;line-height:1.6;margin-bottom:24px;'>
                                                Hi {$record->firstname}, the NTT admin team has requested you to verify your email address. Click below:
                                            </p>
                                            <a href='{$verifyUrl}' style='display:inline-block;background:#8c0000;color:white;padding:14px 32px;text-decoration:none;border-radius:8px;font-weight:bold;font-size:14px;'>Verify My Email</a>
                                            <p style='color:#9ca3af;font-size:12px;margin-top:32px;line-height:1.5;'>
                                                This link expires in 24 hours.
                                            </p>
                                        </div>
                                    ");
                                });
                                Notification::make()->title('Verification email sent')->success()->send();
                            } catch (\Exception $e) {
                                Notification::make()->title('Failed to send email')->danger()->send();
                            }
                        }),
                    Tables\Actions\Action::make('sendAuth')
                        ->label('Send 2FA Instructions')
                        ->icon('heroicon-o-shield-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (User $record) {
                            $loginUrl = env('FRONTEND_URL', 'https://newsthetruth.com') . '/login';
                            $adminUrl = 'https://backend.newsthetruth.com/admin';
                            try {
                                Mail::send([], [], function ($message) use ($record, $loginUrl, $adminUrl) {
                                    $message->to($record->email)->subject('Action Required: Setup NTT Staff Authentication')->html("
                                        <div style='font-family:Arial,sans-serif;max-width:600px;margin:0 auto;padding:40px 20px;'>
                                            <h1 style='font-size:28px;font-weight:900;color:#111827;margin-bottom:8px;'>News The Truth</h1>
                                            <hr style='border:none;border-top:3px solid #8c0000;margin:16px 0 32px;width:60px;'>
                                            <h2 style='font-size:20px;color:#111827;margin-bottom:16px;'>Staff 2FA Authentication Setup</h2>
                                            <p style='color:#4b5563;font-size:15px;line-height:1.6;margin-bottom:24px;'>
                                                Hi {$record->firstname}, your NTT Staff account requires 2FA authentication for access. Please follow these steps:
                                            </p>
                                            <ol style='color:#4b5563;font-size:15px;line-height:1.8;margin-bottom:24px;'>
                                                <li>Login to the <a href='{$loginUrl}'>NTT News Portal</a></li>
                                                <li>Go to your Profile Settings</li>
                                                <li>Enable Two-Factor Authentication</li>
                                                <li>Scan the QR code with your authenticator app</li>
                                                <li>Enter the 6-digit code to confirm setup</li>
                                            </ol>
                                            <a href='{$adminUrl}' style='display:inline-block;background:#8c0000;color:white;padding:14px 32px;text-decoration:none;border-radius:8px;font-weight:bold;font-size:14px;'>Go to Admin Panel</a>
                                            <p style='color:#9ca3af;font-size:12px;margin-top:32px;line-height:1.5;'>If you have any issues, contact the NTT admin team.</p>
                                        </div>
                                    ");
                                });
                                Notification::make()->title('2FA setup email sent')->success()->send();
                            } catch (\Exception $e) {
                                Notification::make()->title('Failed to send auth email')->danger()->send();
                            }
                        }),
                ])->button()->label('Manage Member')->size('sm'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStaff::route('/'),
            'create' => Pages\CreateStaff::route('/create'),
            'edit' => Pages\EditStaff::route('/{record}/edit'),
        ];
    }
}
