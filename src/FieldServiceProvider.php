<?php

namespace Frontkom\NovaMediaLibrary;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;
use Frontkom\NovaMediaLibrary\Http\Controllers\IndexController;
use Frontkom\NovaMediaLibrary\Classes\MediaHandler;
use Frontkom\NovaMediaLibrary\Commands\OptimizeOriginals;
use Frontkom\NovaMediaLibrary\Commands\RegenerateThumbnails;
use Frontkom\NovaMediaLibrary\Commands\RegenerateWebp;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/nova-global-media-library.php' => config_path('nova-global-media-library.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/../config/nova-global-media-library.php',
            'nova-global-media-library'
        );

        $this->publishes([
            __DIR__ . '/../config/nova-global-media-library.php' => config_path('nova-global-media-library.php'),
        ], 'config');

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        Validator::extend('height', '\Frontkom\NovaMediaLibrary\Classes\MediaValidator@height');

        Nova::serving(function (ServingNova $event) {
            Nova::script('media-field', __DIR__ . '/../dist/js/field.js');
            Nova::style('media-field', __DIR__ . '/../dist/css/field.css');
            Nova::script('url-field', __DIR__ . '/../dist/js/urlField.js');
            Nova::script('custom-index-toolbar', __DIR__ . '/../dist/js/toolbar.js');
        });

        Nova::resources([
            config('nova-global-media-library.media_resource', Media::class)
        ]);

        $this->commands([
            RegenerateThumbnails::class,
            OptimizeOriginals::class,
            RegenerateWebp::class,
        ]);

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'nova-media');
    }

    public function routes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova'])
            ->prefix('nova-vendor/frontkom/media-library')
            ->group(function (): void {
                Route::get('{resource}/{resourceId}/media/{field}', IndexController::class);
            });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(MediaHandler::class, function () {
            $mediaHandler = config('nova-global-media-library.media_handler', MediaHandler::class);
            return new $mediaHandler;
        });
    }
}
