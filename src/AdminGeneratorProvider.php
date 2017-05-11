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
        ]);
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
