<?php namespace Brackets\AdminGenerator;

use Illuminate\Support\ServiceProvider;

class AdminGeneratorProvider extends ServiceProvider
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
            Generate\Model::class,
            Generate\Controller::class,
            Generate\ViewIndex::class,
            Generate\ViewCreate::class,
            Generate\ViewEdit::class,
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
