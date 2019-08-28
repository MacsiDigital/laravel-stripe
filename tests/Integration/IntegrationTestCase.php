<?php

namespace MacsiDigital\Stripe\Tests\Integration;

use MacsiDigital\Stripe\Stripe;
use Stripe\Error\InvalidRequest;
use MacsiDigital\Stripe\Tests\TestCase;

abstract class IntegrationTestCase extends TestCase
{
    /**
     * @var string
     */
    protected static $stripePrefix = 'stripe-test-';

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected static function deleteStripeResource(ApiResource $resource)
    {
        try {
            $resource->delete();
        } catch (InvalidRequest $e) {
        }
    }
}