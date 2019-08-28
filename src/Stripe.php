<?php

namespace MacsiDigital\Stripe;

use Illuminate\Support\Str;
use MacsiDigital\Stripe\Exceptions\InterfaceUnknownException;
use MacsiDigital\Stripe\Interfaces\PrivateApplication;

class Stripe
{
     /**
     * The Larave-Stripe library version.
     *
     * @var string
     */
    const VERSION = '1.0.0';

    /**
     * The Stripe API version.
     *
     * @var string
     */
    const STRIPE_VERSION = '2019-08-14';

    public function __construct($type = 'Private')
    {
        $function = 'boot'.ucfirst($type).'Application';
        if (method_exists($this, $function)) {
            $this->$function();
        } else {
            throw new InterfaceUnknownException('Application Interface type not known');
        }
    }

    public function bootPrivateApplication()
    {
        \Stripe\Stripe::setAppInfo(
          "Laravel Stripe",
          static::VERSION,
          "https://github.com/MacsiDigital/laravel-stripe"
        );
        \Stripe\Stripe::setApiKey(config("stripe.secret"));
        \Stripe\Stripe::setApiVersion(static::STRIPE_VERSION);
    }

    public function __get($key)
    {
        return $this->getNode($key);
    }

    public function getNode($key)
    {
        $class = 'MacsiDigital\Stripe\\'.Str::studly($key);
        if (class_exists($class)) {
            return new $class();
        }
        throw new NodeNotFoundException($class.' node not found on this API');
    }
}
