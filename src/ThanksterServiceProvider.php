<?php

namespace Riazxrazor\Thankster;


use Illuminate\Support\ServiceProvider;

class ThanksterServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $dist = __DIR__.'/../config/thankster.php';
        $this->publishes([
            $dist => config_path('thankster.php'),
        ]);

        $this->mergeConfigFrom($dist, 'thankster');
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton(Thankster::class, function($app){
            $config = $app['config']->get('thankster');

            if(!$config){
                throw new \RuntimeException('missing thankster configuration section');
            }

            if(!isset($config['API_KEY'])){
                throw new \RuntimeException('missing thankster configuration: `API_KEY`');
            }

            return new Thankster($config);
        });

        $this->app->alias(Thankster::class, 'thankster-api');
    }

}