<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class QuickCreatePostWidget extends Widget
{
    protected static string $view = 'filament.widgets.quick-create-post-widget';

    protected static ?int $sort = -2;

    protected int | string | array $columnSpan = 1;
}
