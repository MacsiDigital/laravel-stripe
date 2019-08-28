<?php

namespace MacsiDigital\Stripe;

use MacsiDigital\Stripe\Support\StripeExtendedModel;

class Owner extends StripeExtendedModel
{
	// public $attributes = [
	// 	"address" => [],
	// 	"email" => '',
	// 	"name" => '',
	// 	"phone" => '',
	// 	"verified_address" => [],
	// 	"verified_email" => '',
	// 	"verified_name" => '',
	// 	"verified_phone" => ''
	// ];

	public $relationships = [
		'address' => \MacsiDigital\Stripe\Address::class,
		'verified_address' => \MacsiDigital\Stripe\Address::class
	];

}
