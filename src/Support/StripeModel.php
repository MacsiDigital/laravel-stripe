<?php

namespace MacsiDigital\Stripe\Support;

abstract class StripeModel extends Model
{
	public $stripeModelClass = '';

	public function setMetaData($key, $value) 
	{
		$this->createMetaDataFeild();
		$this->metadata[$key] = $value;
		return $this;
	}

	protected function createMetaDataFeild(){
		if(!isset($this->attributes['metadata']) && !is_array($this->attributes['metadata'])){
			$this->attributes['metadata'] = [];
		}
		return $this;
	}
}