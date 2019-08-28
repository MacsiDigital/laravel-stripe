<?php

namespace MacsiDigital\Stripe;

use MacsiDigital\Stripe\PaymentMethod;
use MacsiDigital\Stripe\Support\StripeExtendedModel;

class LastPaymentError extends StripeExtendedModel
{
	public $relationships = [
		'payment_method' => PaymentMethod::class,
	];
}