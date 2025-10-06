<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected static ?int $sort = 9;

    protected int | string | array $columnSpan = 'full';

    public static function getView(): string
    {
        return 'filament.widgets.quick-actions-widget';
    }
}
