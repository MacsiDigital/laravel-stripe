<?php

namespace MacsiDigital\Stripe;

use MacsiDigital\Stripe\Address;
use MacsiDigital\Stripe\Support\StripeExtendedModel;

class BillingDetail extends StripeExtendedModel
{

	// public $attributes = [
	// 	"address" => [],
	// 	"email" => '',
	// 	"name" => '',
	// 	"phone" => ''
	// ];

	public $relationships = [
		'address' => Address::class,
	];

}
