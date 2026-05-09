<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Scout\Scout;
use Meilisearch\Client as MeilisearchClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Paginator::useBootstrap();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(MeilisearchClient::class, function ($app) {
            $config = $app['config']->get('scout.meilisearch');

            return new MeilisearchClient(
                $config['host'],
                $config['key'],
                clientAgents: [sprintf('Meilisearch Laravel Scout (v%s)', Scout::VERSION)],
            );
        });
    }
}
