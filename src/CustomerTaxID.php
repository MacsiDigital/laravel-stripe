<?php

namespace MacsiDigital\Stripe;

use MacsiDigital\Stripe\Support\StripeModel;

class CustomerTaxID extends StripeModel
{
	public $stripeModelClass = \Stripe\Customer::class;

	// public $attributes = [
	// 	"id" => '',
	// 	"object" => '',
	// 	"country" => '',
	// 	"created" => '',
	// 	"customer" => '',
	// 	"livemode" => '',
	// 	"type" => '',
	// 	"value" => '',
	// 	"verification" => [],
	// ];

	public $relationships = [
		'verification' => \MacsiDigital\Stripe\Verification::class
	];
}
