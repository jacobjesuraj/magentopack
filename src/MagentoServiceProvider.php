<?php
namespace Magento;

use Illuminate\Support\ServiceProvider;
//use Illuminate\Support\Facades\App;
//use Magento\Magento;
use Magento\Contracts\MagentoContract;

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
        
        // $this->app->bind('magento',function() {
        //      return new Magento();
        // });


        // $this->app->bind('MagentoContract', 'magento');

        $this->app->singleton(
            MagentoContract::class, function($app) {

             $magentoAuth = new MagentoAuth($app['request'], config( 'magento.magento_username' ),config( 'magento.magento_password' ));

             return new Magento($magentoAuth);

        });
       
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
         $this->publishes([
            __DIR__ . '/config/magento.php' => config_path('magento.php')
        ], 'magento');
    }
}
