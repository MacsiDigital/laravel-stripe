<?php

namespace MacsiDigital\Stripe;

use MacsiDigital\Stripe\Support\StripeExtendedModel;

class InvoiceSetting extends StripeExtendedModel
{
	// public $attributes = [
	// 	"custom_fields" => '',
	// 	"default_payment_method" => '',
	// 	"footer" => ''
	// ];
	 
	public function setCustomField($key, $value) 
	{
		$this->createCustomField();
		$this->custom_fields[$key] = $value;
		return $this;
	}

	protected function createCustomField(){
		if(!isset($this->attributes['custom_fields']) && !is_array($this->attributes['custom_fields'])){
			$this->attributes['custom_fields'] = [];
		}
		return $this;
	}
}
