<?php

namespace MacsiDigital\Stripe;

use MacsiDigital\Stripe\Charge;
use MacsiDigital\Stripe\Customer;
use MacsiDigital\Stripe\Shipping;
use MacsiDigital\Stripe\TransferData;
use MacsiDigital\Stripe\LastPaymentError;
use MacsiDigital\Stripe\PaymentMethod\Card;
use MacsiDigital\Stripe\Support\StripeModel;

class PaymentIntent extends StripeModel
{
	public $stripeModelClass = \Stripe\PaymentIntent::class;

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
		'amount',
		'currency',
		'application_fee_amount',
		'capture_method',
		'confirm',
		'confirmation_method',
		'customer',
		'description',
		'metadata',
		'off_session',
		'on_behalf_of',
		'payment_method',
		'payment_method_options',
		'payment_method_types',
		'receipt_email',
		'return_url',
		'save_payment_method',
		'setup_future_usage',
		'shipping',
		'statement_descriptor',
		'statement_descriptor_suffix',
		'transfer_data',
		'transfer_group'
	];

	public $updateAttributes = [
		'amount',
		'currency',
		'application_fee_amount',
		'customer',
		'description',
		'metadata',
		'payment_method',
		'payment_method_types',
		'receipt_email',
		'save_payment_method',
		'setup_future_usage',
		'shipping',
		'statement_descriptor',
		'statement_descriptor_suffix',
		'transfer_data',
		'transfer_group'
	];

	public $requiredAttributes = [
		'amount',
		'currency'
	];

	public $queryAttributes = [
		'created' => ['<', '<=', '>=', '>'],
		'customer' => ['=']
	];

	public $oneRelationships = [
		'payment_method_options' => PaymentMethodOptions::class,
		'shipping' => Shipping::class,
		'transfer_data' => TransferData::class,
		'last_payment_error' => LastPaymentError::class,
		'next_action' => NextAction::class
	];

	public $manyRelationships = [
		'charges' => Charge::class,
	];

	public function customer() 
	{
		return (new Customer)->find($this->customer);
	}

	public function paymentMethod() 
	{
		return (new PaymentMethod)->find($this->payment_method);
	}

	
}
