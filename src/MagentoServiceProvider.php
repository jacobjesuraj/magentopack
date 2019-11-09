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
        $this->commands([
            Console\MagentoCommand::class,
            Console\MagentoFetchOrderByDate::class,
            Console\UpdateSingleOrder::class,
            Console\UpdateOrdersById::class,
            Console\UpdateOrdersByDate::class,
            Console\CreateOrder::class,
            Console\CreateProduct::class,
            Console\GetStock::class,
            Console\GetAllStock::class,
            Console\UpdateStock::class,
            Console\UpdateMultipleProducts::class
        ]);
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
