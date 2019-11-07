<?php
namespace Magento;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Magento\MagentoFetch;

class MagentoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        //$this->app->make('App\Http\Controllers\MagentoController');

        $this->app->bind('magento',function() {
             return new MagentoFetch();
        });

        // $this->app->bind('magento', function ($app) {

        // return new Facebook(config('facebook'));
        // }

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
