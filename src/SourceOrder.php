<?php

namespace MacsiDigital\Stripe;

use MacsiDigital\Stripe\Support\StripeExtendedModel;

class SourceOrder extends StripeExtendedModel
{
	// public $attributes = [
	// 	"amount" => '',
	// 	"currency" => '',
	// 	"email" => '',
	// 	"items" => [],
	// 	"shipping" => []
	// ];

	public $relationships = [
		'shipping' => \MacsiDigital\Stripe\Shipping::class,
	];

}
