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
            Section::make('Submission')->schema([
                TextInput::make('title')->required()->maxLength(255)->columnSpanFull(),
                Textarea::make('description')->rows(5)->columnSpanFull(),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending Review',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'published' => 'Published as Story',
                    ])
                    ->default('pending'),
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
