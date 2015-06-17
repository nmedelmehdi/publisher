<?php namespace Orchestra\Publisher;

use Illuminate\Support\ServiceProvider;

class PublisherServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerMigration();

        $this->registerAssetPublisher();
    }

    /**
     * Register the service provider for Orchestra Platform migrator.
     *
     * @return void
     */
    protected function registerMigration()
    {
        $this->app->singleton('orchestra.publisher.migrate', function ($app) {
            // In order to use migration, we need to boot 'migration.repository'
            // instance.
            $app->make('migration.repository');

            return new MigrateManager($app, $app->make('migrator'));
        });
    }

    /**
     * Register the service provider for Orchestra Platform asset publisher.
     *
     * @return void
     */
    protected function registerAssetPublisher()
    {
        $this->app->singleton('orchestra.publisher.asset', function ($app) {
            return new AssetManager($app, $app->make('asset.publisher'));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'orchestra.publisher.migrate',
            'orchestra.publisher.asset',
        ];
    }
}
