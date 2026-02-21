<?php

namespace DynamikaWeb\Adaptive;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use DynamikaWeb\Adaptive\View\Components\Menu;

class MenuServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'adaptive-menu');

        Blade::component('adaptive-menu', Menu::class);

        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/dynamikaweb/adaptive-menu'),
        ], 'adaptive-menu-assets');
    }
}