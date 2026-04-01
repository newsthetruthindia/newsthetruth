<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CitizenJournalismResource\Pages;
use App\Models\CitizenJournalism;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CitizenJournalismResource extends Resource
{
    protected static ?string $model = CitizenJournalism::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationGroup = 'Community';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Citizen Reports';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Submission Details')
                ->description('Provide the core information for this citizen report.')
                ->schema([
                    Select::make('user_id')
                        ->label('Submitted Name')
                        ->relationship('user', 'firstname')
                        ->searchable()
                        ->preload()
                        ->required(),

                    TextInput::make('title')
                        ->label('Subject')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\DateTimePicker::make('datetime')
                        ->label('Incident Date & Time')
                        ->default(now())
                        ->required(),

                    Forms\Components\FileUpload::make('attachment_url')
                        ->label('Incident Picture(s)')
                        ->image()
                        ->directory('citizen-journalism')
                        ->required()
                        ->columnSpanFull(),

                    Textarea::make('description')
                        ->label('Report Content')
                        ->rows(5)
                        ->required()
                        ->columnSpanFull(),

                    TextInput::make('place')
                        ->label('Location / Place')
                        ->required()
                        ->maxLength(255),

                    Select::make('status')
                        ->options([
                            'pending' => 'Pending Review',
                            'approved' => 'Approved',
                            'rejected' => 'Rejected',
                            'published' => 'Published as Story',
                        ])
                        ->default('pending')
                        ->required(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->weight('bold')->limit(50),
                TextColumn::make('user.name')->label('Submitted By')->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved', 'published' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')->label('Submitted')->dateTime('M j, Y')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                    'published' => 'Published',
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCitizenJournalisms::route('/'),
            'create' => Pages\CreateCitizenJournalism::route('/create'),
            'edit' => Pages\EditCitizenJournalism::route('/{record}/edit'),
        ];
    }
}
