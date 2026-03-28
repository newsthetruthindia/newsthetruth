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
use Illuminate\Support\Facades\Hash;

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
                                ->afterStateUpdated(function ($state, $set, $record) {
                                    if (!$state) return;
                                    
                                    $path = is_array($state) ? reset($state) : $state;
                                    if (!$path) return;
                                    
                                    $media = Media::create([
                                        'url' => '/' . $path,
                                        'path' => $path,
                                        'type' => 'image',
                                    ]);
                                    
                                    $set('attachment_id', $media->id);
                                })
                                ->dehydrated(false)
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
            ->columns([
                ImageColumn::make('photo')
                    ->label('Photo')
                    ->disk('webapp_public')
                    ->circular()
                    ->state(fn ($record) => $record->details?->media?->url ? ltrim($record->details->media->url, '/') : null)
                    ->placeholder('No Image'),
                TextColumn::make('firstname')->searchable()->sortable(),
                TextColumn::make('lastname')->searchable()->sortable(),
                TextColumn::make('email')->searchable()->sortable(),
                TextColumn::make('roles.name')
                    ->badge()
                    ->label('Role'),
                TextColumn::make('email_verified_at')
                    ->label('Verified')
                    ->dateTime('M j, Y')
                    ->placeholder('Unverified'),
                TextColumn::make('created_at')->dateTime('M j, Y')->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
