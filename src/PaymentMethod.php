<?php

namespace MacsiDigital\Stripe;

use MacsiDigital\Stripe\Customer;
use MacsiDigital\Stripe\BillingDetail;
use MacsiDigital\Stripe\PaymentMethod\Card;
use MacsiDigital\Stripe\Support\StripeModel;
use MacsiDigital\Stripe\PaymentMethod\CardPresent;

class PaymentMethod extends StripeModel
{
	public $stripeModelClass = \Stripe\PaymentMethod::class;

	protected $allowedMethods = ['get', 'find', 'insert', 'update'];

	public $insertAttributes = [
		'type',
		'card',
		'billing_details',
		'metadata'
	];

	public $updateAttributes = [
		'card',
		'billing_details',
		'metadata'
	];

	public $requiredAttributes = [
		'type',
		'card'
	];

	public $oneRelationships = [
		'billing_details' => BillingDetail::class,
		'card' => Card::class,
		'card_present' => CardPresent::class
	];

	public $queryAttributes = [
		'customer' => ['='],
		'type' => ['='],
	];

	public $requiredQueryAttributes = [
		'type',
		'customer'
	];

	public function customer()
	{
		return (new Customer)->find($this->customer);
	}
}
