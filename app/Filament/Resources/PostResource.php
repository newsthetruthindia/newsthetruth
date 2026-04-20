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
use Filament\Forms\Components\Actions\Action;
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
                ->hidden()
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

            Forms\Components\Grid::make(3)
                ->schema([
                    Forms\Components\Group::make([
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
                                                    }
                                                } catch(\Exception $e) {}
                                            })
                                    )
                                    ->toolbarButtons([
                                        'attachFiles', 'bold', 'bulletList', 'codeBlock',
                                        'h2', 'h3', 'italic', 'link', 'orderedList',
                                        'redo', 'strike', 'underline', 'undo', 'blockquote',
                                    ])
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpan(2),

                    Forms\Components\Group::make([
                        Section::make('Voiceover Preview')
                            ->icon('heroicon-m-speaker-wave')
                            ->description('Instant audio playback.')
                            ->schema([
                                Forms\Components\Placeholder::make('audio_player')
                                    ->label('')
                                    ->content(function ($record) {
                                        if (!$record || !$record->audio_clip_url) {
                                            return new \Illuminate\Support\HtmlString('
                                                <div class="flex items-center gap-2 p-3 bg-primary/5 rounded-xl border border-dashed border-primary/20">
                                                    <div class="p-1.5 bg-primary/10 rounded-lg">
                                                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                                                    </div>
                                                    <span class="text-[10px] font-black uppercase tracking-widest text-primary/60">Ready for generation</span>
                                                </div>
                                            ');
                                        }

                                        $rawUrl = $record->audio_clip_url;
                                        // Standardize URL to ensure it works on all environments
                                        $audioUrl = (str_starts_with($rawUrl, 'http') || str_starts_with($rawUrl, '/')) 
                                                    ? $rawUrl 
                                                    : asset('storage/' . $rawUrl);

                                        return new \Illuminate\Support\HtmlString("
                                            <div class='flex flex-col gap-4 p-4 bg-gray-900/50 backdrop-blur-md rounded-2xl border border-white/5 shadow-2xl'>
                                                <div class='flex items-center justify-between'>
                                                    <span class='text-[9px] font-black uppercase tracking-[0.2em] text-primary'>Master Audio Feed</span>
                                                    <span class='text-[8px] font-medium text-white/30 truncate max-w-[150px]'>" . basename($rawUrl) . "</span>
                                                </div>
                                                <audio controls class='w-full h-10 custom-audio-player h-12' preload='auto'>
                                                    <source src='{$audioUrl}' type='audio/mpeg'>
                                                    Your browser does not support the audio element.
                                                </audio>
                                                <div class='flex items-center gap-4'>
                                                    <a href='{$audioUrl}' target='_blank' class='text-[10px] font-black uppercase tracking-widest text-white/50 hover:text-primary transition-colors flex items-center gap-2'>
                                                        <svg class='w-3 h-3' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4'></path></svg>
                                                        Download
                                                    </a>
                                                </div>
                                            </div>
                                            <style>
                                                .custom-audio-player::-webkit-media-controls-enclosure { background-color: rgba(0,0,0,0.2) !important; border-radius: 12px; }
                                                .custom-audio-player::-webkit-media-controls-panel { background-color: transparent !important; }
                                            </style>
                                        ");
                                    }),
                            ]),

                        Section::make('Publishing')
                            ->icon('heroicon-m-globe-alt')
                            ->schema([
                                Select::make('status')
                                    ->options([
                                        'published' => 'Published',
                                        'drafted' => 'Drafted',
                                        'open' => 'Open Review',
                                    ])
                                    ->default('drafted')
                                    ->required(),

                                Toggle::make('breaking')
                                    ->label('Mark as Breaking News')
                                    ->default(false),
                                
                                Forms\Components\Hidden::make('user_id')
                                    ->default(fn() => auth()->id()),
                            ]),

                        Section::make('Reporter Attribution')
                            ->icon('heroicon-m-user-circle')
                            ->schema([
                                Select::make('reporter_name')
                                    ->label('Filing Name')
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
                                    ->placeholder('e.g. New Delhi')
                                    ->maxLength(255),
                            ]),

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
                                    ->createOptionAction(fn (Action $action) => $action->modalHeading('Create Category')->modalButton('Create')),

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
                                    ->createOptionAction(fn (Action $action) => $action->modalHeading('Create Tag')->modalButton('Create')),
                                ]),

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
                            ]),

                        Section::make('Media & Thumbnail')
                            ->schema([
                                Select::make('thumbnail')
                                    ->label('Select Existing Media')
                                    ->relationship('thumbnailMedia', 'url', fn ($query) => $query->latest())
                                    ->searchable()
                                    ->preload()
                                    ->allowHtml()
                                    ->getOptionLabelFromRecordUsing(fn ($record) => "<div style='display:flex; align-items:center; gap:8px;'><img src='".asset($record->url)."' style='height:35px; width:50px; object-fit:cover; border-radius:4px;'> <span>" . basename($record->url) . "</span></div>"),

                                FileUpload::make('new_thumbnail_upload')
                                    ->label('Or Upload New')
                                    ->image()
                                    ->disk('webapp_public')
                                    ->directory('uploads/media')
                                    ->imagePreviewHeight('200'),
                                
                                TextInput::make('image_credit')
                                    ->label('Image Credit')
                                    ->maxLength(255),
                            ]),

                        Section::make('SEO Metadata')
                            ->icon('heroicon-m-magnifying-glass')
                            ->collapsible()
                            ->collapsed()
                            ->schema([
                                TextInput::make('meta_title')
                                    ->label('SEO Title')
                                    ->maxLength(70),

                                Forms\Components\Textarea::make('meta_description')
                                    ->label('SEO Description')
                                    ->maxLength(160)
                                    ->rows(2),
                            ]),
                    ])->columnSpan(1),
                ]),
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
                        'drafted' => 'Drafted',
                        'open' => 'Open Review',
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
                            $imageUrl = $record->thumbnailMedia ? asset($record->thumbnailMedia->url) : null;

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
                
                Tables\Actions\Action::make('share_social')
                    ->label('Social Share')
                    ->icon('heroicon-o-share')
                    ->color('primary')
                    ->button()
                    ->size('sm')
                    ->form([
                        \Filament\Forms\Components\CheckboxList::make('platforms')
                            ->label('Select Platforms to Publish To')
                            ->options([
                                'facebook' => 'Facebook Page',
                                'instagram' => 'Instagram Professional Account'
                            ])
                            ->default(['facebook', 'instagram']),
                        \Filament\Forms\Components\Textarea::make('custom_message')
                            ->label('Custom Social Media Caption')
                            ->placeholder('Write an engaging caption for your followers...')
                            ->rows(3)
                            ->required(),
                    ])
                    ->action(function (\App\Models\Post $record, array $data) {
                        $service = new \App\Services\SocialPublishingService();
                        $link = rtrim(env('FRONTEND_URL', 'https://newsthetruth.com'), '/') . '/news/' . $record->slug;
                        $success = true;
                        $platformsPosted = [];
                
                        if (in_array('facebook', $data['platforms'])) {
                            $fb = $service->publishToFacebook($data['custom_message'], $link);
                            if ($fb) {
                                $platformsPosted[] = 'Facebook';
                            } else {
                                $success = false;
                            }
                        }
                
                        if (in_array('instagram', $data['platforms'])) {
                            $imageUrl = $record->thumbnailMedia ? asset($record->thumbnailMedia->url) : null;
                            if ($imageUrl) {
                               $ig = $service->publishToInstagram($data['custom_message'] . "\n\nRead more at our website: " . $link, $imageUrl);
                               if ($ig) {
                                   $platformsPosted[] = 'Instagram';
                               } else {
                                   $success = false;
                               }
                            } else {
                               \Filament\Notifications\Notification::make()->title('Instagram requires a thumbnail image.')->danger()->send();
                               $success = false;
                            }
                        }
                
                        if ($success && count($platformsPosted) > 0) {
                            \Filament\Notifications\Notification::make()
                                ->title('Published successfully to ' . implode(' & ', $platformsPosted))
                                ->success()
                                ->send();
                        } else if (count($platformsPosted) > 0) {
                             \Filament\Notifications\Notification::make()
                                ->title('Published with some errors. Verification needed.')
                                ->warning()
                                ->send();
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->title('Publishing Failed. Check your API Keys in Settings.')
                                ->danger()
                                ->send();
                        }
                    }),
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
