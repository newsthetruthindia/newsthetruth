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
use Filament\Forms\Set;
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
            Section::make('🚨 Reporter System Notice')
                ->schema([
                    Forms\Components\Placeholder::make('system_warning')
                        ->hiddenLabel()
                        ->content(new \Illuminate\Support\HtmlString('
                            <div style="padding: 12px; background-color: rgba(220, 38, 38, 0.1); border-left: 4px solid #dc2626; color: #ef4444; border-radius: 6px; font-size: 14px;">
                                <strong>⚠️ AVOID 500 ERRORS:</strong><br><br>
                                &bull; <b>Subtitle & Excerpt</b>: The database limit has been increased, but avoid pasting massive blocks of text.<br>
                                &bull; <b>Pasting Formatting</b>: If you copy from Word or another website, use <i>"Paste as Plain Text"</i> (Ctrl+Shift+V or Cmd+Shift+V).<br>
                                &bull; <b>Attribution</b>: The "Posted By" field tracks your login for security. Use "Attribution Name" to set the public author.<br>
                            </div>
                        ')),
                ])->columnSpanFull(),

            Section::make('Story Details')
                ->description('Write and publish your story.')
                ->schema([
                    TextInput::make('title')
                        ->label('Headline')
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', str($state)->slug()))
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

            Section::make('Reporter Information')
                ->description('Specify the attribution for this story.')
                ->schema([
                    Select::make('reporter_name')
                        ->label('Attribution Name')
                        ->required()
                        ->options(function () {
                            $reporters = \App\Models\User::role('Reporter')->get()
                                ->mapWithKeys(fn ($u) => [$u->name => $u->name])
                                ->toArray();
                            
                            return array_merge([
                                'NTT Desk' => 'NTT Desk (Official)',
                                'Staff Reporter' => 'Staff Reporter (Ghost)',
                                'Citizen Journalist' => 'Citizen Journalist (Public)',
                            ], $reporters);
                        })
                        ->searchable(),
                    
                    TextInput::make('location')
                        ->label('Filing Location')
                        ->placeholder('e.g. New Delhi, India')
                        ->maxLength(255),
                ])->columns(2),

            Forms\Components\Hidden::make('user_id')
                ->default(fn() => auth()->id()),

            Section::make('Audio Preview')
                ->schema([
                    Forms\Components\Placeholder::make('audio_preview')
                        ->label('Generated Audio')
                        ->content(fn ($record) => $record && $record->audio_clip_url 
                            ? new \Illuminate\Support\HtmlString("<audio controls src='{$record->audio_clip_url}' class='w-full'></audio>")
                            : 'No audio generated yet.'),
                ])
                ->visible(fn ($record) => $record !== null),

            Section::make('Categorization')
                ->icon('heroicon-m-tag')
                ->schema([
                    Select::make('filamentCategories')
                        ->relationship('filamentCategories', 'title')
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->label('Categories')
                        ->createOptionForm([
                            Forms\Components\TextInput::make('title')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', str($state)->slug())),
                            Forms\Components\TextInput::make('slug')
                                ->required()
                                ->unique('categories', 'slug', ignoreRecord: true),
                        ])
                        ->createOptionAction(fn (Forms\Components\Actions\Action $action) => $action->modalHeading('Create Category')->modalButton('Create')),

                    Select::make('filamentTags')
                        ->relationship('filamentTags', 'title')
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->label('Tags')
                        ->createOptionForm([
                            Forms\Components\TextInput::make('title')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', str($state)->slug())),
                            Forms\Components\TextInput::make('slug')
                                ->required()
                                ->unique('tags', 'slug', ignoreRecord: true),
                        ])
                        ->createOptionAction(fn (Forms\Components\Actions\Action $action) => $action->modalHeading('Create Tag')->modalButton('Create')),
                ])->columns(2),

            Section::make('Social Media & Video')
                ->icon('heroicon-m-share')
                ->schema([
                    TextInput::make('video_url')
                        ->label('YouTube URL')
                        ->placeholder('https://www.youtube.com/watch?v=...')
                        ->url(),

                    TextInput::make('x_embed_url')
                        ->label('X (Twitter) URL')
                        ->placeholder('https://x.com/user/status/...')
                        ->url(),
                ])->columns(2),

            Section::make('Media')
                ->schema([
                    Select::make('thumbnail')
                        ->label('Select Existing Media')
                        ->relationship('thumbnailMedia', 'url', fn ($query) => $query->latest())
                        ->searchable()
                        ->preload()
                        ->allowHtml()
                        ->getOptionLabelFromRecordUsing(fn ($record) => "<div style='display:flex; align-items:center; gap:8px;'><img src='".asset($record->url)."' style='height:35px; width:50px; object-fit:cover; border-radius:4px;'> <span>" . basename($record->url) . "</span></div>")
                        ->columnSpanFull(),

                    FileUpload::make('new_thumbnail_upload')
                        ->label('Or Upload New Media')
                        ->image()
                        ->disk('webapp_public')
                        ->directory('uploads/media')
                        ->imagePreviewHeight('200')
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
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    ImageColumn::make('thumbnailMedia.url')
                        ->disk('webapp_public')
                        ->height(200)
                        ->width('100%')
                        ->extraImgAttributes([
                            'class' => 'object-cover w-full h-[200px] rounded-t-xl',
                        ]),

                    Tables\Columns\Layout\Stack::make([
                        TextColumn::make('title')
                            ->weight('bold')
                            ->size('lg')
                            ->lineClamp(3)
                            ->extraAttributes(['class' => 'mb-2 leading-snug'])
                            ->searchable(),

                        Tables\Columns\Layout\Split::make([
                            TextColumn::make('filamentCategories.title')
                                ->badge()
                                ->color('warning')
                                ->extraAttributes(['class' => 'flex-wrap']),

                            TextColumn::make('status')
                                ->badge()
                                ->grow(false)
                                ->color(fn (string $state): string => match ($state) {
                                    'published' => 'success',
                                    'drafted' => 'gray',
                                    'open' => 'warning',
                                    default => 'gray',
                                }),
                        ])->extraAttributes(['class' => 'mb-4']),

                        Tables\Columns\Layout\Stack::make([
                            TextColumn::make('reporter_name')
                                ->size('sm')
                                ->weight('medium')
                                ->color('white')
                                ->icon('heroicon-m-user')
                                ->iconColor('primary')
                                ->searchable(),

                            TextColumn::make('created_at')
                                ->label('Published')
                                ->dateTime('M j, Y')
                                ->size('xs')
                                ->color('gray')
                                ->icon('heroicon-m-calendar')
                                ->iconColor('gray'),
                        ])->space(1),
                    ])->extraAttributes(['class' => 'p-4 flex flex-col flex-1 justify-between'])->space(2),
                ])->extraAttributes([
                    'class' => 'bg-gray-900/50 rounded-xl border border-white/5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col h-full',
                ]),
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
                Tables\Actions\EditAction::make()
                    ->button()
                    ->size('sm'),
                    
                Tables\Actions\ActionGroup::make([
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
                    Tables\Actions\Action::make('send_to_subscribers')
                        ->label('Notify')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('info')
                        ->requiresConfirmation()
                        ->hidden(fn (Post $record) => $record->status !== 'published')
                        ->action(function (Post $record) {
                            $subscribers = \App\Models\User::where('type', 'user')->get();
                            $imageUrl = $record->thumbnailMedia ? asset('storage/' . $record->thumbnailMedia->url) : null;

                            \Illuminate\Support\Facades\Notification::send(
                                $subscribers, 
                                new \App\Notifications\BroadcastNotification(
                                    $record->title,
                                    env('APP_URL') . '/posts/' . $record->slug,
                                    $record->excerpt,
                                    $imageUrl
                                )
                            );

                            \Filament\Notifications\Notification::make()
                                ->title('Broadcast Sent')
                                ->body('Notification is being delivered to ' . $subscribers->count() . ' subscribers.')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\DeleteAction::make(),
                ])->button()->label('More Options')->size('sm'),
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
