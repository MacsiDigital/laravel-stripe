<?php

namespace MacsiDigital\Stripe\Facades;

use Illuminate\Support\Facades\Facade;

class Builder extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'stripe.builder';
    }
}
