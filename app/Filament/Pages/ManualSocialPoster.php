<?php

namespace App\Filament\Pages;

use App\Services\SocialPublishingService;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class ManualSocialPoster extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Manual Social Poster';
    protected static ?int $navigationSort = 11;
    protected static ?string $title = 'Manual Social Media Posting';

    public ?string $url = '';
    public ?string $caption = '';
    public array $platforms = ['facebook', 'instagram'];
    public $image = null;

    public function mount(): void
    {
        //
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Post Details')
                    ->description('Manually share any link or message to your social profiles.')
                    ->schema([
                        TextInput::make('url')
                            ->label('Link URL')
                            ->placeholder('https://newsthetruth.com/news/...')
                            ->url()
                            ->required(),
                        
                        Textarea::make('caption')
                            ->label('Post Caption')
                            ->placeholder('Write something engaging...')
                            ->rows(4)
                            ->required(),
                        
                        CheckboxList::make('platforms')
                            ->label('Publish to...')
                            ->options([
                                'facebook' => 'Facebook Page',
                                'instagram' => 'Instagram Professional Account'
                            ])
                            ->columns(2)
                            ->required(),
                        
                        FileUpload::make('image')
                            ->label('Post Image (Required for Instagram)')
                            ->image()
                            ->directory('uploads/manual-social')
                            ->hint('Instagram requires a high-quality square or portrait image.'),
                    ])
            ]);
    }

    public function post(): void
    {
        $data = $this->form->getState();
        $service = new SocialPublishingService();
        $successCount = 0;
        $totalPlatforms = count($data['platforms']);

        if (in_array('facebook', $data['platforms'])) {
            $fb = $service->publishToFacebook($data['caption'], $data['url']);
            if ($fb) $successCount++;
        }

        if (in_array('instagram', $data['platforms'])) {
            if (!$data['image']) {
                Notification::make()->title('Instagram Error')->body('An image is required to post on Instagram.')->danger()->send();
                return;
            }

            // Build full public URL for the image
            $imageUrl = config('app.url') . Storage::url($data['image']);
            
            $ig = $service->publishToInstagram($data['caption'] . "\n\n" . $data['url'], $imageUrl);
            if ($ig) $successCount++;
        }

        if ($successCount === $totalPlatforms) {
            Notification::make()
                ->title('Published Successfully')
                ->body("Your post is now live on " . implode(' & ', array_map('ucfirst', $data['platforms'])))
                ->success()
                ->send();
            
            $this->reset(['url', 'caption', 'image']);
        } else if ($successCount > 0) {
            Notification::make()->title('Partial Success')->body('Published to some platforms, but others failed. Check Social Settings.')->warning()->send();
        } else {
            Notification::make()->title('Publishing Failed')->body('Could not post to any platform. Please check your API tokens in Social Settings.')->danger()->send();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('publish')
                ->label('Publish Post Now')
                ->icon('heroicon-o-check-circle')
                ->color('primary')
                ->requiresConfirmation()
                ->action(fn () => $this->post()),
        ];
    }
}
