<?php

namespace MacsiDigital\Stripe;

use MacsiDigital\Stripe\Address;
use MacsiDigital\Stripe\Support\StripeExtendedModel;

class Shipping extends StripeExtendedModel
{
	// public $attributes = [
	// 	"address" => [],
	// 	"name" => '',
	// 	"phone" => '',
	// 	"carrier" => '',
	// 	"tracking_number" => ''
	// ];

	public $relationships = [
		'address' => Address::class,
	];

}
