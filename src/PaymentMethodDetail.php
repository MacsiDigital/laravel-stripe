<?php

namespace MacsiDigital\Stripe;

use MacsiDigital\Stripe\Support\StripeExtendedModel;

class PaymentMethodDetail extends StripeExtendedModel
{
	// public $attributes = [
	// 	"type" => '',
	// 	"card" => [],
	// 	"card_present" => []
	// ];

	public $relationships = [
		'card' => \MacsiDigital\Stripe\PaymentMethod\Card::class,
		'card_present' => \MacsiDigital\Stripe\PaymentMethod\CardPresent::class
	];

}
