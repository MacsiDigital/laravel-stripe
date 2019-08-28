<?php

namespace MacsiDigital\Stripe\Contracts;

interface Builder
{
    public function __construct(StripeAPI $connection);

}