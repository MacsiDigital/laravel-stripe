<?php

namespace MacsiDigital\Stripe;

use MacsiDigital\Stripe\Support\StripeModel;

class Source extends StripeModel
{
	public $stripeModelClass = \Stripe\Source::class;

	// public $attributes = [
	// 	"id" => '',
	// 	"object" => '',
	// 	"amount" => '',
	// 	"client_secret" => '',
	// 	"code_verification" => [],
	// 	"created" => '',
	// 	"currency" => '',
	// 	"customer" => '',
	// 	"flow" => '',
	// 	"livemode" => '',
	// 	"metadata" => '',
	// 	"owner" => [],
	// 	"receiver" => [],
	// 	"redirect" => [],
	// 	"source_order" => [],
	// 	"statement_descriptor" => '',
	// 	"status" => '',
	// 	"type" => '',
	// 	"usage" => ''
	// ];

	public $queryFields = [

	];

	public $relationships = [
		'code_verification' => \MacsiDigital\Stripe\Address::class,
		'owner' => \MacsiDigital\Stripe\Owner::class,
		'receiever' => \MacsiDigital\Stripe\Receiver::class,
		'redirect' => \MacsiDigital\Stripe\Redirect::class,
		'source_order' => \MacsiDigital\Stripe\SourceOrder::class
	];

}
