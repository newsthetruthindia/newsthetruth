<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\User;
use App\Models\CitizenJournalism;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PostStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Stories', Post::count())
                ->description('News articles published')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            Stat::make('Published', Post::where('status', 'published')->count())
                ->description('Live on the site')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Drafts', Post::where('status', 'drafted')->count())
                ->description('Awaiting publication')
                ->descriptionIcon('heroicon-m-pencil')
                ->color('warning'),

            Stat::make('Staff Members', User::count())
                ->description('Registered accounts')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Citizen Reports', CitizenJournalism::where('posted', 0)->count())
                ->description('Awaiting review')
                ->descriptionIcon('heroicon-m-megaphone')
                ->color('danger'),
        ];
    }
}
