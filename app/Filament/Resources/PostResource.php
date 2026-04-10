<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;

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
            Forms\Components\Grid::make(3)
                ->schema([
                    // Main Column: Story Content
                    Forms\Components\Group::make()
                        ->schema([
                            Section::make('Story Details')
                                ->description('Draft your headline and full story content.')
                                ->schema([
                                    TextInput::make('title')
                                        ->label('Headline')
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', str($state)->slug()))
                                        ->required()
                                        ->maxLength(500)
                                        ->placeholder('Enter an engaging headline...')
                                        ->columnSpanFull(),

                                    TextInput::make('subtitle')
                                        ->label('Subtitle')
                                        ->maxLength(500)
                                        ->placeholder('Optional sub-headline...')
                                        ->columnSpanFull(),

                                    TextInput::make('slug')
                                        ->label('URL Slug')
                                        ->required()
                                        ->maxLength(500)
                                        ->columnSpanFull()
                                        ->unique(Post::class, 'slug', ignoreRecord: true),

                                    Forms\Components\Textarea::make('excerpt')
                                        ->label('Summary / Excerpt')
                                        ->placeholder('A short summary for news cards...')
                                        ->rows(3)
                                        ->columnSpanFull(),

                                    RichEditor::make('description')
                                        ->label('Full Story Content')
                                        ->required()
                                        ->fileAttachmentsDisk('webapp_public')
                                        ->fileAttachmentsDirectory('uploads/articles')
                                        ->fileAttachmentsVisibility('public')
                                        ->afterStateUpdated(function ($state) {
                                            if (!$state) return;
                                            // RichEditor attachments can be multiple
                                            foreach ((array)$state as $file) {
                                                optimize_image_on_upload(public_path($file));
                                            }
                                        })
                                        ->live(onBlur: true)
                                        ->hintAction(
                                            Forms\Components\Actions\Action::make('grammar_check')
                                                ->label('Grammar & Fact Check')
                                                ->icon('heroicon-o-check-badge')
                                                ->action(function (Get $get) {
                                                    $state = $get('description');
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
                                ]),
                        ])
                        ->columnSpan(['lg' => 2]),

                    // Sidebar: Metadata & Settings
                    Forms\Components\Group::make()
                        ->schema([
                            Section::make('Publishing')
                                ->schema([
                                    Select::make('status')
                                        ->options([
                                            'published' => 'Published',
                                            'drafted' => 'Drafted',
                                            'open' => 'Open Review',
                                        ])
                                        ->default('drafted')
                                        ->required()
                                        ->native(false),

                                    Toggle::make('breaking')
                                        ->label('Breaking News')
                                        ->default(false),
                                ]),

                            Section::make('Categorization')
                                ->schema([
                                    Select::make('categories')
                                        ->label('Primary Category')
                                        ->relationship('filamentCategories', 'title')
                                        ->multiple()
                                        ->preload()
                                        ->required()
                                        ->searchable(),

                                    Select::make('filamentTags')
                                        ->label('Tags')
                                        ->relationship('filamentTags', 'title')
                                        ->multiple()
                                        ->preload()
                                        ->searchable()
                                        ->createOptionForm([
                                            TextInput::make('title')
                                                ->required()
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', str($state)->slug())),
                                            TextInput::make('slug')
                                                ->required()
                                                ->unique('tags', 'slug'),
                                            Forms\Components\Hidden::make('user_id')
                                                ->default(fn() => auth()->id()),
                                        ]),
                                ]),

                            Section::make('Featured Image')
                                ->collapsible()
                                ->schema([
                                    Forms\Components\Select::make('thumbnail')
                                        ->label('Pick from Library')
                                        ->options(function () {
                                            return \App\Models\Media::where('type', 'image')
                                                ->latest()
                                                ->take(60)
                                                ->get()
                                                ->pluck('url', 'id')
                                                ->map(function ($url) {
                                                    $fullUrl = asset('storage/' . $url);
                                                    return "<img src='{$fullUrl}' style='height:120px; width:100px; aspect-ratio:1/1; object-fit:cover; border-radius:8px; display:inline-block; margin:2px;'>";
                                                })
                                                ->toArray();
                                        })
                                        ->allowHtml()
                                        ->searchable()
                                        ->preload()
                                        ->columnSpanFull(),

                                    FileUpload::make('new_thumbnail_upload')
                                        ->label('Or Upload File')
                                        ->image()
                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                                        ->disk('webapp_public')
                                        ->directory('uploads/media')
                                        ->afterStateUpdated(function ($state) {
                                            if (!$state) return;
                                            optimize_image_on_upload(public_path($state));
                                        })
                                        ->dehydrated(false)
                                        ->columnSpanFull(),
                                    
                                    TextInput::make('image_credit')
                                        ->label('Image Credit')
                                        ->maxLength(255),
                                ]),

                            Section::make('Attribution')
                                ->schema([
                                    Select::make('reporter_name')
                                        ->label('Reporting By')
                                        ->required()
                                        ->options(function () {
                                            $reporters = collect();
                                            try {
                                                $reporters = User::role('Reporter')->get();
                                            } catch (\Exception $e) {}

                                            $reporters = $reporters->mapWithKeys(function ($u) {
                                                    $fullName = trim($u->firstname . ' ' . $u->lastname) ?: $u->email;
                                                    return [$fullName => $fullName];
                                                })->toArray();
                                            
                                            return array_merge([
                                                'NTT Desk' => 'NTT Desk (Official)',
                                                'Staff Reporter' => 'Staff Reporter',
                                                'Citizen Journalist' => 'Citizen Journalist',
                                            ], $reporters);
                                        })
                                        ->searchable()
                                        ->live()
                                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                                            $user = User::role('Reporter')
                                                ->where(DB::raw("trim(concat(firstname, ' ', coalesce(lastname, '')))"), trim($state))
                                                ->first();
                                            
                                            // Always ensure a valid ID exists, default to 1 (usually Admin) or auth()->id()
                                            $set('user_id', $user ? $user->id : (auth()->id() ?: 1));
                                        }),
                                    
                                    TextInput::make('location')
                                        ->label('Filing Location')
                                        ->placeholder('e.g. New Delhi')
                                        ->maxLength(255),
                                        
                                    Forms\Components\Hidden::make('user_id')
                                        ->default(fn() => auth()->id()),
                                ]),

                            Section::make('SEO Settings')
                                ->collapsed()
                                ->schema([
                                    TextInput::make('meta_title')
                                        ->label('SEO Title')
                                        ->maxLength(70),

                                    Forms\Components\Textarea::make('meta_description')
                                        ->label('Meta Description')
                                        ->maxLength(160)
                                        ->rows(3),
                                ]),

                            Section::make('Social Media Preview')
                                ->collapsible()
                                ->schema([
                                    Forms\Components\TextInput::make('twitter_preview_url')
                                        ->label('Paste Twitter/X Link')
                                        ->placeholder('https://x.com/user/status/...')
                                        ->live(debounce: 500),
                                        
                                    Forms\Components\Placeholder::make('twitter_preview')
                                        ->label('Live Preview')
                                        ->content(function (Get $get) {
                                            $link = $get('twitter_preview_url');
                                            $content = $get('description');
                                            
                                            // Extract from dedicated field OR from story content
                                            // More lenient regex to capture full URLs including query params
                                            preg_match_all('/https?:\/\/(?:twitter\.com|x\.com|fixupx\.com|vxtwitter\.com)\/[a-zA-Z0-9_\-\.]+\/status\/\d+[^\s<>"]*/', (string)$content, $matches);
                                            $contentLinks = $matches[0] ?? [];
                                            
                                            $links = array_filter(array_unique(array_merge([$link], $contentLinks)));
                                            
                                            if (empty($links)) return 'Paste an X link above or in your story to see the preview.';
                                            
                                            $html = '<div class="space-y-4 pt-2" id="twitter-preview-container">';
                                            foreach ($links as $l) {
                                                $cacheKey = 'tweet_v4_'.md5($l);
                                                $embedHtml = Cache::get($cacheKey);

                                                if (!$embedHtml) {
                                                    try {
                                                        $response = Http::withHeaders([
                                                            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36',
                                                        ])->timeout(12)->get('https://publish.twitter.com/oembed', [
                                                            'url' => $l,
                                                            'theme' => 'dark',
                                                        ]);
                                                        if ($response->successful()) {
                                                            $embedHtml = $response->json('html');
                                                            Cache::put($cacheKey, $embedHtml, now()->addDay());
                                                        } else {
                                                            $embedHtml = 'ERROR_' . $response->status();
                                                        }
                                                    } catch (\Exception $e) {
                                                        $embedHtml = 'TIMEOUT';
                                                    }
                                                }

                                                if ($embedHtml && !str_starts_with($embedHtml, 'ERROR_') && $embedHtml !== 'TIMEOUT') {
                                                    $html .= $embedHtml;
                                                } else {
                                                    $msg = $embedHtml === 'TIMEOUT' ? 'X Server Timeout. Please check your internet or try again later.' : 'Link Error or X Private Post (Code: ' . substr($embedHtml, 6) . ')';
                                                    $html .= "<div class='p-4 border border-dashed border-primary/20 bg-primary/5 rounded-xl text-[10px] text-foreground/60'>
                                                                <span class='font-black text-primary uppercase block mb-1'>X Status Check</span>
                                                                {$msg}
                                                                <br><code class='text-primary opacity-50 block mt-1'>" . (strlen($l) > 60 ? substr($l, 0, 60) . "..." : $l) . "</code>
                                                              </div>";
                                                }
                                            }
                                            $html .= '</div>';
                                            $html .= '<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>';
                                            $html .= '<script>if(window.twttr && window.twttr.widgets) { window.twttr.widgets.load(); }</script>';
                                            
                                            return new HtmlString($html);
                                        })
                                ]),
                        ])
                        ->columnSpan(['lg' => 1]),
                ])
        ])->columns(1);
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
                    ->searchable()
                    ->sortable()
                    ->color(fn ($state) => in_array($state, ['NTT Desk', 'Staff Reporter', 'Citizen Journalist']) ? 'gray' : 'primary'),

                TextColumn::make('user.firstname')
                    ->label('Posted By')
                    ->toggleable()
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($state, $record) {
                        $name = trim($record->user?->firstname . ' ' . $record->user?->lastname) ?: $record->user?->email;
                        
                        // User Request: "do posted by admin. [ where data is not available - who is posted ]"
                        // These profiles don't post articles themselves
                        $nonLogins = ['Ankit Salvi', 'Soonakshi Ghosh', 'Tamal Saha', 'Titas Mukherjee', 'Aniket Datta', 'Sankha Subhra Das', 'Suprav Banerjee', 'NTT Desk'];
                        
                        if (in_array(trim($name), $nonLogins) && trim($name) !== 'Admin NTT') {
                            return 'Admin NTT';
                        }
                        
                        return $name;
                    }),
                
                TextColumn::make('location')
                    ->label('Location')
                    ->toggleable()
                    ->searchable()
                    ->sortable(),
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
