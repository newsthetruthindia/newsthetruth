<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class PostSharesChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Daily Article Shares';
    
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        // For shares, we sum the shares column using Trend
        $data = Trend::model(Post::class)
            ->between(
                start: now()->subDays(30),
                end: now(),
            )
            ->perDay()
            ->sum('shares');

        return [
            'datasets' => [
                [
                    'label' => 'Total Shares',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#FF6384',
                    'borderColor' => '#FF6384',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
