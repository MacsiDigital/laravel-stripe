<?php

namespace MacsiDigital\Stripe;

use MacsiDigital\Stripe\RedirectToURI;
use MacsiDigital\Stripe\Support\StripeExtendedModel;

class NextAction extends StripeExtendedModel
{
	public $relationships = [
		'redirect_to_uri' => RedirectToURI::class,
	];
}