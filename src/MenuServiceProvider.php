<?php

namespace DynamikaSolucoesWeb\Adaptive;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use DynamikaSolucoesWeb\Adaptive\View\Components\Menu;

class MenuServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'adaptive-menu');

        Blade::component('adaptive-menu', Menu::class);

        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/dynamikasolucoesweb/adaptive-menu'),
        ], 'adaptive-menu-assets');

        Blade::directive('adaptiveMenuAssets', function () {
            return "<?php
                \$cssFiles = ['style.css'];
                \$jsFiles  = ['script.js'];
                
                \$publicPath = 'vendor/dynamikasolucoesweb/adaptive-menu';
                \$isPublished = file_exists(public_path(\$publicPath));
    
                foreach (\$cssFiles as \$file) {
                    \$url = \$isPublished 
                        ? asset(\$publicPath . '/css/' . \$file) 
                        : route('adaptive-menu.assets', ['type' => 'css', 'file' => \$file]);
                    echo ' <link rel=\"stylesheet\" href=\"' . \$url . '\">' . PHP_EOL;
                }
    
                foreach (\$jsFiles as \$file) {
                    \$url = \$isPublished 
                        ? asset(\$publicPath . '/js/' . \$file) 
                        : route('adaptive-menu.assets', ['type' => 'js', 'file' => \$file]);
                    echo ' <script src=\"' . \$url . '\"></script>' . PHP_EOL;
                }
            ?>";
        });

        $this->registerAssetRoutes();
    }

    protected function registerAssetRoutes()
    {
        Route::get('adaptive-menu/assets/{type}/{file}', function ($type, $file) {
            $path = __DIR__.'/../resources/assets/' . $type . '/' . $file;
            
            if (!file_exists($path)) {
                abort(404);
            }

            $mimes = [
                'css' => 'text/css',
                'js'  => 'application/javascript',
            ];

            $contentType = $mimes[$type] ?? 'text/plain';

            return response()->file($path, [
                'Content-Type' => $contentType,
                'Cache-Control' => 'public, max-age=31536000',
            ]);
        })->name('adaptive-menu.assets');
    }
}