<?php

namespace MacsiDigital\Stripe;

use MacsiDigital\Stripe\Support\StripeModel;

class Refund extends StripeModel
{
	public $stripeModelClass = \Stripe\Refund::class;

	// public $attributes = [
	// 	"id" => '',
	// 	"object" => '',
	// 	"amount" => '',
	// 	"balance_transaction" => '',
	// 	"charge" => '',
	// 	"created" => '',
	// 	"currency" => '',
	// 	"metadata" => '',
	// 	"reason" => '',
	// 	"receipt_number" => '',
	// 	"source_transfer_reversal" => '',
	// 	"status" => '',
	// 	"transfer_reversal" => ''
	// ];
}
