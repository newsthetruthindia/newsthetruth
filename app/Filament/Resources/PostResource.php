<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Story Details')
                ->description('Write and publish your story.')
                ->schema([
                    TextInput::make('title')
                        ->label('Headline')
                        ->required()
                        ->maxLength(500)
                        ->columnSpanFull(),

                    TextInput::make('subtitle')
                        ->label('Subtitle')
                        ->maxLength(500)
                        ->columnSpanFull(),

                    TextInput::make('slug')
                        ->label('URL Slug')
                        ->required()
                        ->maxLength(500)
                        ->columnSpanFull()
                        ->unique(Post::class, 'slug', ignoreRecord: true),

                    Forms\Components\Textarea::make('excerpt')
                        ->label('Summary / Excerpt')
                        ->rows(3)
                        ->columnSpanFull(),

                    RichEditor::make('description')
                        ->label('Full Story')
                        ->hintAction(
                            Forms\Components\Actions\Action::make('checkGrammar')
                                ->icon('heroicon-m-check-badge')
                                ->label('Check Grammar')
                                ->color('primary')
                                ->action(function ($state) {
                                    $text = strip_tags($state);
                                    if(empty(trim($text))) {
                                        \Filament\Notifications\Notification::make()->title('Editor is empty')->warning()->send();
                                        return;
                                    }
                                    try {
                                        $response = \Illuminate\Support\Facades\Http::asForm()->post('https://api.languagetoolplus.com/v2/check', [
                                            'text' => $text,
                                            'language' => 'en-US',
                                        ]);
                                        if ($response->successful()) {
                                            $matches = $response->json('matches');
                                            if (empty($matches)) {
                                                \Filament\Notifications\Notification::make()->title('Perfect! No spelling or grammar errors found.')->success()->send();
                                                return;
                                            }
                                            foreach($matches as $match) {
                                                $replacements = collect($match['replacements'])->pluck('value')->take(3)->implode(', ');
                                                $msg = $match['message'];
                                                if($replacements) {
                                                    $msg .= " Suggestions: " . $replacements;
                                                }
                                                // Extract snippet of where the error is
                                                $context = "";
                                                if(isset($match['context']['text']) && isset($match['context']['offset']) && isset($match['context']['length'])) {
                                                    $context = "\nContext: \"... " . substr($match['context']['text'], max(0, $match['context']['offset'] - 10), $match['context']['length'] + 20) . " ...\"";
                                                }
                                                \Filament\Notifications\Notification::make()
                                                    ->title('Review Needed')
                                                    ->body($msg . $context)
                                                    ->warning()
                                                    ->duration(10000)
                                                    ->send();
                                            }
                                        } else {
                                           \Filament\Notifications\Notification::make()->title('API Error')->body('LanguageTool API failed.')->danger()->send(); 
                                        }
                                    } catch(\Exception $e) {
                                        \Filament\Notifications\Notification::make()->title('Error')->body($e->getMessage())->danger()->send();
                                    }
                                })
                        )
                        ->toolbarButtons([
                            'attachFiles', 'bold', 'bulletList', 'codeBlock',
                            'h2', 'h3', 'italic', 'link', 'orderedList',
                            'redo', 'strike', 'underline', 'undo', 'blockquote',
                        ])
                        ->columnSpanFull(),
                ])->columns(2),

            Section::make('Categorization')
                ->schema([
                    Select::make('filamentCategories')
                        ->relationship('filamentCategories', 'title')
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->label('Categories'),

                    Select::make('filamentTags')
                        ->relationship('filamentTags', 'title')
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->label('Tags'),
                ])->columns(2),

            Section::make('Media')
                ->schema([
                    Select::make('thumbnail')
                        ->label('Select Existing Media')
                        ->relationship('thumbnailMedia', 'url')
                        ->searchable()
                        ->columnSpanFull(),

                    FileUpload::make('new_thumbnail_upload')
                        ->label('Or Upload New Media')
                        ->image()
                        ->disk('webapp_public')
                        ->directory('uploads/media')
                        ->imagePreviewHeight('200')
                        ->dehydrated(false)
                        ->columnSpanFull(),
                ]),

            Section::make('SEO & Publishing')
                ->collapsed()
                ->schema([
                    TextInput::make('meta_title')
                        ->label('SEO Title')
                        ->maxLength(70),

                    Forms\Components\Textarea::make('meta_description')
                        ->label('Meta Description')
                        ->maxLength(160)
                        ->rows(2),

                    Select::make('status')
                        ->options([
                            'published' => 'Published',
                            'drafted' => 'Drafted',
                            'open' => 'Open Review',
                        ])
                        ->default('drafted')
                        ->required(),

                    Toggle::make('breaking')
                        ->label('Breaking News')
                        ->default(false),
                    
                    TextInput::make('image_credit')
                        ->label('Image Credit')
                        ->maxLength(255),

                    TextInput::make('location')
                        ->label('Location')
                        ->maxLength(255),

                    TextInput::make('reporter_name')
                        ->label('Reporter Name')
                        ->maxLength(255),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnails.url')
                    ->label('Image')
                    ->disk('webapp_public')
                    ->circular(false)
                    ->size(60),

                TextColumn::make('title')
                    ->label('Headline')
                    ->searchable()
                    ->limit(60)
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('filamentCategories.title')
                    ->label('Category')
                    ->badge()
                    ->color('warning')
                    ->separator(','),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'drafted' => 'gray',
                        'open' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Published')
                    ->dateTime('M j, Y')
                    ->sortable(),

                TextColumn::make('reporter_name')
                    ->label('Reporter')
                    ->toggleable()
                    ->searchable(),
                
                TextColumn::make('location')
                    ->label('Location')
                    ->toggleable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'published' => 'Published',
                        'draft' => 'Draft',
                        'pending' => 'Pending',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('generate_audio')
                    ->label('Audio')
                    ->icon('heroicon-o-speaker-wave')
                    ->action(function (\App\Models\Post $record) {
                        try {
                            $controller = app(\App\Http\Controllers\PostController::class);
                            $req = new \Illuminate\Http\Request([
                                'text' => strip_tags($record->description),
                                'type' => 'text',
                                'post_id' => $record->id,
                            ]);
                            $controller->updatePostAudio($req);
                            \Filament\Notifications\Notification::make()
                                ->title('Audio Generated')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Generation Failed')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->requiresConfirmation(),
                Tables\Actions\Action::make('publish')
                    ->label('Publish')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Post $record) => $record->status !== 'published')
                    ->action(fn (Post $record) => $record->update(['status' => 'published'])),
                Tables\Actions\Action::make('hide')
                    ->label('Hide')
                    ->icon('heroicon-o-eye-slash')
                    ->color('danger')
                    ->visible(fn (Post $record) => $record->status === 'published')
                    ->action(fn (Post $record) => $record->update(['status' => 'drafted'])),
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
