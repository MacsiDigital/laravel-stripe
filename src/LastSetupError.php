<?php

namespace MacsiDigital\Stripe;

use MacsiDigital\Stripe\PaymentMethod;
use MacsiDigital\Stripe\Support\StripeExtendedModel;

class LastSetupError extends StripeExtendedModel
{
	public $relationships = [
		'payment_method' => PaymentMethod::class,
	];
}