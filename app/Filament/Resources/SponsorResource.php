<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SponsorResource\Pages;
use App\Models\Sponsor;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\TernaryFilter;

class SponsorResource extends Resource
{
    protected static ?string $model = Sponsor::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    
    protected static ?string $navigationGroup = 'Promotion';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Ad Details')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Select::make('type')
                            ->options([
                                'banner' => 'Banner (Home Feed) - 4:1 Ratio',
                                'sidebar' => 'Sidebar - 1:1 Ratio',
                                'splash' => 'Splash Screen - 2:3 Ratio',
                            ])
                            ->required()
                            ->live(),
                        TextInput::make('link_url')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('new_image_upload')
                            ->label('Upload Ad Image')
                            ->image()
                            ->imageEditor()
                            ->imageCropAspectRatio(fn (Forms\Get $get) => match ($get('type')) {
                                'banner' => '4:1',
                                'sidebar' => '1:1',
                                'splash' => '2:3',
                                default => null,
                            })
                            ->disk('webapp_public')
                            ->directory('uploads/media')
                            ->imagePreviewHeight('250')
                            ->columnSpanFull(),
                        Select::make('media_id')
                            ->relationship('media', 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->name ?? "Media #{$record->id}")
                            ->label('Or Select from Media Library')
                            ->searchable()
                            ->preload(),
                    ])->columns(2),

                Forms\Components\Section::make('Status & Schedule')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                        DateTimePicker::make('starts_at')
                            ->label('Starts At'),
                        DateTimePicker::make('ends_at')
                            ->label('Ends At'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_preview')
                    ->label('Image')
                    ->getStateUsing(fn ($record) => ltrim($record->media?->url ?? $record->image_url ?? '', '/'))
                    ->disk(fn ($state) => str_starts_with($state ?? '', 'http') ? null : 'webapp_public')
                    ->size(50),
                TextColumn::make('debug_url')
                    ->label('Debug URL')
                    ->getStateUsing(fn ($record) => ltrim($record->media?->url ?? $record->image_url ?? '', '/'))
                    ->limit(30),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'banner' => 'success',
                        'sidebar' => 'warning',
                        'splash' => 'danger',
                        default => 'gray',
                    }),
                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('starts_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('ends_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'banner' => 'Banner',
                        'sidebar' => 'Sidebar',
                        'splash' => 'Splash',
                    ]),
                TernaryFilter::make('is_active')
                    ->label('Active Status'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSponsors::route('/'),
            'create' => Pages\CreateSponsor::route('/create'),
            'edit' => Pages\EditSponsor::route('/{record}/edit'),
        ];
    }
}
