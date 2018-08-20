<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // Using class based composers...
        view()->composer(
            ['inc.aside-categorias', 'mobile.inc.aside-categorias'], 'App\Http\ViewComposers\CategoriasComposer'
        );

        view()->composer(
            ['inc.abas-resultados', 'mobile.inc.abas-resultados'], 'App\Http\ViewComposers\NewMessagesComposer'
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
