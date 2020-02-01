<?php

namespace MacsiDigital\Stripe\PaymentMethod;

use MacsiDigital\Stripe\PaymentMethod\CardChecks;
use MacsiDigital\Stripe\Support\StripeExtendedModel;

class Card extends StripeExtendedModel
{
	// public $attributes = [
	// 	"brand" => '',
	// 	"checks" => [],
	// 	"country" => '',
	// 	"exp_month" => '',
	// 	"exp_year" => '',
	// 	"fingerprint" => '',
	// 	"funding" => '',
	// 	"last4" => '',
	// 	"three_d_secure" => '',
	// 	"wallet" => '',
	// 	"request_three_d_secure => '"
	// ];

	protected $oneRelationships = [
		'checks' => CardChecks::class
	];

	public $insertAttributes = [
		'number',
		'exp_month',
		'exp_year',
		'cvc'
	];

	public $updateAttributes = [
		'exp_month',
		'exp_year',
	];

}
