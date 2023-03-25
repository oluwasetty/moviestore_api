<?php

namespace App\Providers;

use App\Movies;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;
use Elastic\Elasticsearch\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Movies\MoviesRepository::class, function ($app) {
            // This is useful in case we want to turn-off our
            // search cluster or when deploying the search
            // to a live, running application at first.
            if (!config('services.search.enabled')) {
                return new Movies\EloquentRepository();
            }

            return new Movies\ElasticsearchRepository(
                $app->make(Client::class)
            );
        });

        $this->bindSearchClient();
    }

    private function bindSearchClient()
    {
        $this->app->bind(Client::class, function ($app) {
            return ClientBuilder::create()
                ->setHosts($app['config']->get('services.search.hosts'))
                ->build();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
