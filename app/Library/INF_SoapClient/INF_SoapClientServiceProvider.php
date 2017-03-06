<?php namespace INF_SoapClient;

use Illuminate\Support\ServiceProvider;

class INF_SoapClientServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('INF_SoapClient', 'INF_SoapClient\INF_SoapClient');
            $loader->alias('INF_ISoapClient', 'INF_SoapClient\INF_ISoapClient');
            $loader->alias('INF_SoapObject', 'INF_SoapClient\INF_SoapObject');
        });

    }

}