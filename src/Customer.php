<?php

namespace MacsiDigital\Stripe;

use MacsiDigital\Stripe\Charge;
use MacsiDigital\Stripe\Source;
use MacsiDigital\Stripe\Address;
use MacsiDigital\Stripe\Charges;
use MacsiDigital\Stripe\Shipping;
use MacsiDigital\Stripe\Subscription;
use MacsiDigital\Stripe\CustomerTaxID;
use MacsiDigital\Stripe\InvoiceSetting;
use MacsiDigital\Stripe\Support\StripeModel;

class Customer extends StripeModel
{
	public $stripeModelClass = \Stripe\Customer::class;

	protected $allowedMethods = ['get', 'find', 'insert', 'update', 'delete'];

	// public $attributes = [
	// 	"id" => '',
	// 	"object" => '',
	// 	"address" => [],
	// 	"balance" => '',
	// 	"created" => '',
	// 	"currency" => '',
	// 	"default_source" => '',
	// 	"delinquent" => '',
	// 	"description" => '',
	// 	"discount" => '',
	// 	"email" => '',
	// 	"invoice_prefix" => '',
	// 	"invoice_settings" => '',
	// 	"livemode" => '',
	// 	"metadata" => '',
	// 	"name" => '',
	// 	"phone" => '',
	// 	"preferred_locales" => '',
	// 	"shipping" => [],
	// 	"sources" => [],
	// 	"subscriptions" => [],
	// 	"tax_exempt" => '',
	// 	"tax_ids" => [],
	// ];

	public $insertAttributes = [
		'address',
		'balance',
		'coupon',
		'description',
		'email',
		'invoice_prefix',
		'invoice_settings',
		'metadata',
		'name',
		'payment_method',
		'phone',
		'preferred_locales',
		'shipping',
		'source',
		'tax_exempt',
		'tax_id_data'
	];

	public $updateAttributes = [
		'address',
		'balance',
		'coupon',
		'description',
		'email',
		'invoice_prefix',
		'invoice_settings',
		'metadata',
		'name',
		'payment_method',
		'phone',
		'preferred_locales',
		'shipping',
		'source',
		'tax_exempt',
		'tax_id_data'
	];

	public $queryAttributes = [
		'created' => ['<', '<=', '>=', '>'],
		'email' => ['='],
	];

	public $oneRelationships = [
		'address' => Address::class,
		'invoice_settings' => InvoiceSetting::class,
		'shipping' => Shipping::class,
	];

	public $manyRelationships = [
		'sources' => Source::class,
		'subscriptions' => Subscription::class,
		'tax_ids' => CustomerTaxID::class,
	];

	public function setMetaData($key, $value) 
	{
		$this->metaData[$key] = $value;
	}

	public function charges() 
	{
		return (new Charge)->where('customer', $this->id);
	}

	public function paymentIntents() 
	{
		return (new PaymentIntent)->where('customer', $this->id);
	}

	public function invoices() 
	{
		return (new Invoice)->where('customer', $this->id);	
	}

	public function subscriptions() 
	{
		return (new Subscription)->where('customer', $this->id);
	}

	public function sources() 
	{
		return (new Source)->where('customer', $this->id);
	}

	public function taxIDs() 
	{
		
	}

	public function balanceTransactions() 
	{
		
	}
}
