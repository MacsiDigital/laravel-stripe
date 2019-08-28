<?php

namespace MacsiDigital\Stripe;

use MacsiDigital\Stripe\Support\StripeExtendedModel;

class PaymentMethodOptions extends StripeExtendedModel
{
	// public $attributes = [
	// 	"type" => '',
	// 	"card" => [],
	// 	"card_present" => []
	// ];

	public $relationships = [
		'card' => \MacsiDigital\Stripe\PaymentMethod\Card::class,
	];

}
