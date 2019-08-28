<?php

namespace MacsiDigital\Stripe\Contracts;

interface Stripe
{
    public function __construct($type = 'Private');

    public function __get($key);

    public function getNode($key);
}
