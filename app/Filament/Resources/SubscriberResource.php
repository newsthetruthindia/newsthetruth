<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriberResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;

class SubscriberResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Community';

    protected static ?string $navigationLabel = 'Subscribers';

    protected static ?string $pluralModelLabel = 'Subscribers';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Subscriber Information')->schema([
                TextInput::make('firstname')->required()->maxLength(255),
                TextInput::make('lastname')->maxLength(255),
                TextInput::make('email')->email()->required()->unique(User::class, 'email', ignoreRecord: true),
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->label(fn (string $context): string => $context === 'create' ? 'Password' : 'New Password (leave blank to keep)'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'user'))
            ->columns([
                TextColumn::make('firstname')->searchable()->sortable(),
                TextColumn::make('lastname')->searchable()->sortable(),
                TextColumn::make('email')->searchable()->sortable(),
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Verified')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('email_verified_at')
                    ->label('Email Verification')
                    ->nullable()
                    ->placeholder('All Users')
                    ->trueLabel('Verified Only')
                    ->falseLabel('Unverified Only'),
                Filter::make('inactive_7_days')
                    ->label('Unverified > 7 Days')
                    ->query(fn (Builder $query) => $query->whereNull('email_verified_at')->where('created_at', '<', now()->subDays(7))),
                Filter::make('possible_fake')
                    ->label('Possible Fake Accounts')
                    ->query(fn (Builder $query) => $query->where(function ($q) {
                        $q->where('email', 'like', '%test%')
                          ->orWhere('email', 'like', '%asdf%')
                          ->orWhere('email', 'like', '%tempmail%')
                          ->orWhere('email', 'like', '%teleworm%')
                          ->orWhere('email', 'like', '%sharklasers%')
                          ->orWhere('firstname', 'regexp', '^[a-zA-Z]{1,2}$') // Too short names
                          ->orWhereNull('lastname');
                    })),
            ])
            ->headerActions([
                Action::make('cleanup_unverified')
                    ->label('Prune Unverified')
                    ->icon('heroicon-m-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Delete Inactive Users')
                    ->modalDescription('This will delete all users who have NOT verified their email and joined more than 7 days ago.')
                    ->action(function () {
                        $count = User::where('type', 'user')
                            ->whereNull('email_verified_at')
                            ->where('created_at', '<', now()->subDays(7))
                            ->delete();
                        
                        Notification::make()
                            ->title('Cleanup Complete')
                            ->body("Removed {$count} inactive users.")
                            ->success()
                            ->send();
                    }),
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
            'index' => Pages\ListSubscribers::route('/'),
            'create' => Pages\CreateSubscriber::route('/create'),
            'edit' => Pages\EditSubscriber::route('/{record}/edit'),
        ];
    }
}
