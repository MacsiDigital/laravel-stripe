<?php

namespace MacsiDigital\Stripe\Support;

use BadMethodCallException;
use MacsiDigital\Stripe\Support\Query;

abstract class OldModel
{
	public $isStripeObject = true;

	public $stripeObject = '';

	public $stripeModelClass = '';

	protected $keyField = 'id';

	protected $attributes = [];

	protected $originalAttributes = [];

	protected $queryFields = [];

	protected $relationships = [];

	protected $createAttributes = [];

	protected $updateAttributes = [];

	public function __set($key, $value) 
	{
		$this->setAttribute($key, $value);
	}

	public function __get($field) 
	{
		return $this->getAttribute($field);
	}

    public function __isset($key)
    {
        return $this->attributeExists($key);
    }

	public function __call($method, $parameters) 
	{
		if($this->isStripeObject && method_exists(new Query($this), $method)){
			return $this->newQuery()->{$method}(...$parameters);
		}
		throw new BadMethodCallException(sprintf(
            'Call to undefined method %s::%s()', static::class, $method
        ));
	}

	public function getKey() 
	{
		return $this->keyField;
	}

	 public function getID()
    {
        return $this->{$this->getKey()};
    }

    public function hasID()
    {
        if (isset($this->{$this->getKey()}) && $this->{$this->getKey()} != '') {
            return true;
        }
        return false;
    }

	public function is($object) 
	{
		if(get_class($object) == get_class($this)){
			return $object->getID() === $this->getID(); 	
		}
	}

	public function query() 
	{
		return $this->newQuery();
	}

	public function newQuery() 
	{
		return new Query($this);
	}

	public static function make($attributes) 
	{
		$model = new static;
		$model->hydrate($attributes);
		return $model;
	}

	public static function create($attributes) 
	{
		$model = self::make($attributes);
		$model->save();
		return $model;
	}

	public function hydrate($attributes) 
	{
		foreach($attributes as $key => $value){
			$this->setRawAttribute($key, $attributes[$key]);
		}
		return $this;	
	}

	public function fill($attributes) 
	{
		foreach($attributes as $key => $value){
			$this->$key = $value;
		}
		return $this;
	}

	public function update() 
	{
		$this->fill($attributes);
		$this->save();
		return $this;
	}

	public function save() 
	{
		if($this->isStripeObject){
			$model = $this->stripeModelClass;
			if ($this->hasID()) {
				$response = $model::update($this->id, $this->updateAttributes());
	        } else {
	            $response = $model::create($this->createAttributes());
	        }
	        $this->hydrate($response->jsonSerialize());
		}
		return $this;
	}

	public function delete() 
	{
		if($this->isStripeObject && $this->HasID()){
			$model = $this->stripeModelClass;	
			$index = $this->GetKey();
			$object = $model::retrieve($this->$index);
			$response = $object->delete();
		}
	}

	public function attributeExists($key)
    {
        return array_key_exists($key, $this->attributes);
    }

    public function isRelationshipAttribute($key)
    {
        return array_key_exists($key, $this->relationships);
    }

	public function setAttribute($key, $value)
	{
		
		if ($this->isRelationshipAttribute($key)) {
			$this->attributes[$key] = [];
			$class = new $this->relationships[$key];
            if (is_array($value) && array_key_exists('data', $value)) {
                foreach ($value as $index => $data) {
                    $this->attributes[$key][$index] = ($this->relationships[$key])::make($data);
                }
                 $this->attributes[$key] = collect($this->attributes[$key]);
            } else if(is_array($value)){
                $this->attributes[$key] = $class::make($value);
            } else {
                $this->attributes[$key] = $value;    
            }
        } else {
            $this->attributes[$key] = $value;
        }
	}

	public function setRawAttribute($key, $value)
	{
		if ($this->isRelationshipAttribute($key)) {
			$this->attributes[$key] = [];
			$class = new $this->relationships[$key];
            if (is_array($value) && array_key_exists('data', $value)) {
                foreach ($value['data'] as $index => $data) {
                	$this->attributes[$key][$index] = ($this->relationships[$key])::make($data);	
                }
                $this->attributes[$key] = $this->originalAttributes[$key] = collect($this->attributes[$key]);
            } else if(is_array($value)){
            	$this->attributes[$key] = $this->originalAttributes[$key] = $class::make($value);
            } else {
                $this->attributes[$key] = $this->originalAttributes[$key] = $value;
            }
        } else {
            $this->attributes[$key] = $this->originalAttributes[$key] = $value;
        }
	}

	public function getAttribute($key) 
	{
		return $this->attributes[$key];
	}

	public function getAttributes() 
	{
		return $this->attributes;
	}

	public function isDirty($key) 
	{
		return $this->originalAttributes[$key] != $this->attributes[$key];
	}

	public function refresh()
	{
		$this->attributes = $this->originalAttributes;
		return $this;
	}

	public function createAttributes()
    {
        $attributes = [];
        foreach ($this->attributes as $key => $value) {
            if (in_array($key, $this->createAttributes)) {
                if (is_object($value)) {
                    $attributes[$key] = $value->createAttributes();
                } else {
                    if ($value != '') {
                        $attributes[$key] = $value;
                    }
                }
            }
        }

        return $attributes;
    }

    public function updateAttributes()
    {
        $attributes = [];
        foreach ($this->attributes as $key => $value) {
            if (in_array($key, $this->updateAttributes)) {
                if (is_object($value)) {
                    $attributes[$key] = $value->updateAttributes();
                } else {
                    if ($value != '') {
                        $attributes[$key] = $value;
                    }
                }
            }
        }

        return $attributes;
    }

}