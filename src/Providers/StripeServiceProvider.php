<?php

namespace MacsiDigital\Stripe\Providers;

use MacsiDigital\Stripe\Stripe;
use Illuminate\Support\ServiceProvider;
use MacsiDigital\Stripe\Support\Builder;
use MacsiDigital\Stripe\Support\StripeAPI;
use MacsiDigital\Stripe\Contracts\Builder  as BuilderContract;
use MacsiDigital\Stripe\Contracts\Stripe as StripeContract;
use MacsiDigital\Stripe\Contracts\StripeAPI as StripeAPIContract;

class StripeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/config.php' => config_path('stripe.php'),
            ], 'config');
        }

        new Stripe;
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../../config/config.php', 'stripe');

        $this->app->singleton(Stripe::class, function () {
            return new Stripe();
        });

        $this->app->bind(StripeContract::class, Stripe::class);
        $this->app->bind(StripeAPIContract::class, StripeAPI::class);
        $this->app->bind(BuilderContract::class, Builder::class);

        $this->app->bind('stripe.builder', Builder::class);
        
        $this->app->bind(Builder::class, function () {
            return new Builder(new StripeAPI);
        });
    }
}
