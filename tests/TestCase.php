<?php

namespace MacsiDigital\Stripe\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use MacsiDigital\Stripe\Providers\StripeServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [StripeServiceProvider::class];
    }
}