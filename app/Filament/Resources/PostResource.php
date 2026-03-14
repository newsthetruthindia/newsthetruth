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

                    RichEditor::make('content')
                        ->label('Full Story')
                        ->toolbarButtons([
                            'attachFiles', 'bold', 'bulletList', 'codeBlock',
                            'h2', 'h3', 'italic', 'link', 'orderedList',
                            'redo', 'strike', 'underline', 'undo', 'blockquote',
                        ])
                        ->columnSpanFull(),
                ])->columns(2),

            Section::make('Categorization')
                ->schema([
                    Select::make('categories')
                        ->relationship('categories', 'cat_data.title')
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->label('Categories'),

                    Select::make('tags')
                        ->relationship('tags', 'name')
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->label('Tags'),
                ])->columns(2),

            Section::make('Media')
                ->schema([
                    FileUpload::make('thumbnail')
                        ->label('Featured Image')
                        ->image()
                        ->directory('uploads/thumbnails')
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
                            'draft' => 'Draft',
                            'pending' => 'Pending Review',
                        ])
                        ->default('draft')
                        ->required(),

                    Toggle::make('breaking')
                        ->label('Breaking News')
                        ->default(false),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnails.url')
                    ->label('Image')
                    ->circular(false)
                    ->size(60),

                TextColumn::make('title')
                    ->label('Headline')
                    ->searchable()
                    ->limit(60)
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('categories.cat_data.title')
                    ->label('Category')
                    ->badge()
                    ->color('warning')
                    ->separator(','),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'gray',
                        'pending' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Published')
                    ->dateTime('M j, Y')
                    ->sortable(),
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
