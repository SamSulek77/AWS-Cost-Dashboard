<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class AwsCostDashboard extends Page
{
    protected static string $view = 'filament.pages.aws-cost-dashboard';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    // Do NOT use getHeaderWidgets() here if rendering widgets manually in Blade.
}
