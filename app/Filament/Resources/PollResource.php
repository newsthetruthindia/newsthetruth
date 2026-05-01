<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PollResource\Pages;
use App\Models\Poll;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PollResource extends Resource
{
    protected static ?string $model = Poll::class;

    protected static ?string $navigationIcon  = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Engagement';
    protected static ?string $navigationLabel = 'Reader Polls';
    protected static ?int    $navigationSort  = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Poll Details')
                ->schema([
                    Forms\Components\TextInput::make('question')
                        ->label('Poll Question')
                        ->required()
                        ->maxLength(500)
                        ->placeholder('e.g. Should the government ban single-use plastics?')
                        ->columnSpanFull(),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Active (Visible to readers)')
                        ->helperText('Only ONE poll should be active at a time.')
                        ->default(false),

                    Forms\Components\DateTimePicker::make('expires_at')
                        ->label('Expires At')
                        ->helperText('Leave blank to keep open indefinitely.')
                        ->nullable(),
                ]),

            Forms\Components\Section::make('Poll Options')
                ->description('Add at least 2 options for readers to choose from.')
                ->schema([
                    Forms\Components\Repeater::make('options')
                        ->relationship('options')
                        ->schema([
                            Forms\Components\TextInput::make('option_text')
                                ->label('Option')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('votes')
                                ->label('Current Votes')
                                ->numeric()
                                ->default(0)
                                ->disabled()
                                ->dehydrated(false),
                        ])
                        ->minItems(2)
                        ->maxItems(6)
                        ->addActionLabel('Add Option')
                        ->columns(2),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question')
                    ->searchable()
                    ->limit(60)
                    ->weight('bold'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('options_sum_votes')
                    ->label('Total Votes')
                    ->sum('options', 'votes')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime('M j, Y H:i')
                    ->placeholder('Never')
                    ->color('gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make()->button()->size('sm'),
                Tables\Actions\Action::make('toggle_active')
                    ->label(fn (Poll $record) => $record->is_active ? 'Deactivate' : 'Activate')
                    ->icon(fn (Poll $record) => $record->is_active ? 'heroicon-o-pause-circle' : 'heroicon-o-play-circle')
                    ->color(fn (Poll $record) => $record->is_active ? 'danger' : 'success')
                    ->action(function (Poll $record) {
                        if (!$record->is_active) {
                            // Deactivate all other polls first
                            Poll::where('id', '!=', $record->id)->update(['is_active' => false]);
                        }
                        $record->update(['is_active' => !$record->is_active]);
                    })
                    ->button()->size('sm'),
                Tables\Actions\DeleteAction::make()->button()->size('sm'),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPolls::route('/'),
            'create' => Pages\CreatePoll::route('/create'),
            'edit'   => Pages\EditPoll::route('/{record}/edit'),
        ];
    }
}
