<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Facades\DB;
use Filament\Widgets\ChartWidget;

class PostViewsChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Daily Article Views';
    
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = DB::table('post_views')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(viewer_count) as aggregate'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Fill in missing dates with zero
        $labels = [];
        $values = [];
        for ($i = 30; $i >= 0; $i--) {
            $dateString = now()->subDays($i)->format('Y-m-d');
            $labels[] = $dateString;
            $match = $data->firstWhere('date', $dateString);
            $values[] = $match ? $match->aggregate : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Views',
                    'data' => $values,
                    'backgroundColor' => '#FFCE56',
                    'borderColor' => '#FFCE56',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
