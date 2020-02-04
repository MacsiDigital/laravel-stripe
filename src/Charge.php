<?php

namespace MacsiDigital\Stripe;

use MacsiDigital\Stripe\Refund;
use MacsiDigital\Stripe\Outcome;
use MacsiDigital\Stripe\Customer;
use MacsiDigital\Stripe\Shipping;
use MacsiDigital\Stripe\FraudDetail;
use MacsiDigital\Stripe\TransferData;
use MacsiDigital\Stripe\BillingDetail;
use MacsiDigital\Stripe\PaymentMethodDetail;
use MacsiDigital\Stripe\Support\StripeModel;

class Charge extends StripeModel
{
	public $stripeModelClass = \Stripe\Charge::class;

	protected $allowedMethods = ['get', 'find', 'insert', 'update'];

	// public $attributes = [
	// 	"id" => '',
	// 	"object" => '',
	// 	"amount" => '',
	// 	"amount_refunded" => '',
	// 	"application" => '',
	// 	"application_fee" => '',
	// 	"application_fee_amount" => '',
	// 	"balance_transaction" => '',
	// 	"billing_details" => [],
	// 	"captured" => '',
	// 	"created" => '',
	// 	"currency" => '',
	// 	"customer" => '',
	// 	"description" => '',
	// 	"dispute" => '',
	// 	"failure_code" => '',
	// 	"failure_message" => '',
	// 	"fraud_details" => [],
	// 	"invoice" => '',
	// 	"livemode" => '',
	// 	"metadata" => [],
	// 	"on_behalf_of" => '',
	// 	"order" => '',
	// 	"outcome" => [],
	// 	"paid" => '',
	// 	"payment_intent" => '',
	// 	"payment_method" => '',
	// 	"payment_method_details" => [],
	// 	"receipt_email" => '',
	// 	"receipt_number" => '',
	// 	"receipt_url" => '',
	// 	"refunded" => '',
	// 	"refunds" => [],
	// 	"review" => '',
	// 	"shipping" => [],
	// 	'source_transfer' => '',
	// 	"statement_descriptor" => '',
	// 	"statement_descriptor_suffix" => '',
	// 	"status" => '',
	// 	"transfer" => '',
	// 	"transfer_data" => [],
	// 	"transfer_group" => '',
	// ];
	 
	public $insertAttributes = [
		'receipt_email',
        'description',
        'customer',
        'amount',
        'currency',
        'metadata'
	];

	public $queryAttributes = [
		'created' => ['<', '<=', '>=', '>'],
		'customer' => ['='],
		'payment_intent' => ['='],
		'transfer_group' => ['='],
	];

	public $oneRelationships = [
		'billing_details' => BillingDetail::class,
		'fraud_details' => FraudDetail::class,
		'outcome' => Outcome::class,
		'payment_method_details' => PaymentMethodDetail::class,
		'refunds' => Refund::class,
		'shipping' => Shipping::class,
		'transfer_data' => TransferData::class
	];

	public $manyRelationships = [
		
	];

	public function customer() 
	{
		return (new Customer)->find($this->customer);
	}
}
