<?php

namespace MacsiDigital\Stripe;

use MacsiDigital\Stripe\Customer;
use MacsiDigital\Stripe\Shipping;
use MacsiDigital\Stripe\NextAction;
use MacsiDigital\Stripe\TransferData;
use MacsiDigital\Stripe\LastSetupError;
use MacsiDigital\Stripe\Support\StripeModel;
use MacsiDigital\Stripe\PaymentMethodOptions;

class SetupIntent extends StripeModel
{
	public $stripeModelClass = \Stripe\SetupIntent::class;

	protected $allowedMethods = ['get', 'find', 'insert', 'update'];
	
	public $insertAttributes = [
		'confirm',
		'customer',
		'description',
		'metadata',
		'on_behalf_of',
		'payment_method',
		'payment_method_options',
		'payment_method_types',
		'return_url',
		'usage'
	];

	public $updateAttributes = [
		'customer',
		'description',
		'metadata',
		'payment_method',
		'payment_method_types'
	];

	public $requiredAttributes = [
		'amount',
		'currency',
		'payment_method_types'
	];

	public $queryAttributes = [
		'created' => ['<', '<=', '>=', '>'],
		'customer' => ['='],
		'payment_method' => ['=']
	];

	public $oneRelationships = [
		'payment_method_options' => PaymentMethodOptions::class,
		'shipping' => Shipping::class,
		'transfer_data' => TransferData::class,
		'last_setup_error' => LastSetupError::class,
		'next_action' => NextAction::class
	];

	public $manyRelationships = [
		
	];

	public function customer() 
	{
		return (new Customer)->find($this->customer);
	}
}
