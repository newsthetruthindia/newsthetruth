<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsletterLogResource\Pages;
use App\Models\NewsletterLog;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NewsletterLogResource extends Resource
{
    protected static ?string $model = NewsletterLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Newsletter Logs';
    
    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            DatePicker::make('sent_date')->disabled(),
            TextInput::make('recipients_count')->disabled(),
            Repeater::make('posts_snapshot')
                ->label('Articles Sent')
                ->schema([
                    TextInput::make('title')->disabled()->columnSpanFull(),
                    TextInput::make('reporter_name')->disabled(),
                ])
                ->disableItemAddition()
                ->disableItemDeletion()
                ->disableItemMovement()
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sent_date')->date('M j, Y')->sortable(),
                TextColumn::make('recipients_count')->label('Users Sent To'),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([])
            ->defaultSort('sent_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNewsletterLogs::route('/'),
        ];
    }
}
