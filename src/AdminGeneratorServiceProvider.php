<?php namespace Brackets\AdminGenerator;

use Illuminate\Support\ServiceProvider;

class AdminGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands([
            GenerateAdmin::class,
            GenerateUser::class,
            GenerateProfile::class,
            Generate\Model::class,
            Generate\Controller::class,
            Generate\ViewIndex::class,
            Generate\ViewForm::class,
            Generate\ViewFullForm::class,
            Generate\ModelFactory::class,
            Generate\Routes::class,
            Generate\IndexRequest::class,
            Generate\StoreRequest::class,
            Generate\UpdateRequest::class,
            Generate\DestroyRequest::class,
            Generate\Lang::class,
            Generate\Permissions::class,
        ]);

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'brackets/admin-generator');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
