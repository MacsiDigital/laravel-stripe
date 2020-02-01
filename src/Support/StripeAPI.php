<?php

namespace MacsiDigital\Stripe\Support;

use MacsiDigital\Stripe\Contracts\StripeAPI as Contract;

class StripeAPI implements Contract
{

	protected $apiMaxLimit = 100;

	protected $model;

	protected $results = [];

	public function setModel($model) 
	{
		$this->model = $model->stripeModelClass;
	}

	public function getModel() 
	{
		return $this->model;
	}

	public function isModelSet() 
	{
		return $this->model != null;
	}

	public function modelCheck() 
	{
		if(!$this->isModelSet()){
			throw new ModelNotSetException('No model set on the API');	
		}
	}
		

	public function findingByIDOnly($wheres) 
	{
		return count($wheres) == 1 && $wheres[0]['field'] == 'id';
	}

	/**
     * Execute the query to the API
     *
     * @param  array|string  $fields
     * @return \Illuminate\Support\Collection
     */
    public function find($id)
    {
    	$this->modelCheck();
    	
    	$object = $this->model::retrieve($id);
		return array_merge($object->jsonSerialize(), ['StripeObject' => $object]);
    }	

	/**
     * Execute the query to the API
     *
     * @param  array|string  $fields
     * @return \Illuminate\Support\Collection
     */
    public function get($wheres, $limit = 0, $pagination = '')
    {
    	$this->modelCheck();
    	if($this->findingByIDOnly($wheres)){
    		return $this->find($wheres[0]['value']);
    	}
    	if($this->results != []){
			return $this->results;
		}
		return $this->collect($this->model::all($this->processOptions($wheres, $limit, $pagination)));
    }

    /**
     * Insert a new record
     *
     * @param  array  $values
     * @return bool
     */
    public function insert(array $attributes)
    {
    	$this->modelCheck();
        $attributes = $this->flattenAttributes($attributes);
        $object = $this->model::create($attributes);
        return array_merge($object->jsonSerialize(), ['StripeObject' => $object]);
    }

    /**
     * Update a record
     *
     * @param  array  $values
     * @return int
     */
    public function update($id, array $attributes)
    {
    	$this->modelCheck();
        $attributes = $this->flattenAttributes($attributes);
        $object = $this->model::update($id, $attributes);
        return array_merge($object->jsonSerialize(), ['StripeObject' => $object]);
    }

    /**
     * Delete a record
     *
     * @return mixed
     */
    public function delete($id)
    {
    	// In the API the delete function is run against the object
    }

    public function collect($response) 
	{
		$return_data = [];
		foreach($response['data'] as $object){
			$return_data[] = array_merge($object->jsonSerialize(), ['StripeObject' => $object]);
		}
		return collect($return_data);
	}

    public function processOptions($where = [], $limit = 0, $pagination = []) 
	{
		$options = [];
		foreach($where as $data){
			if($data['operator'] == '='){
				$options[$data['field']] = $data['value'];	
			} else if ($data['operator'] == '>'){
				$options[$data['field']] = ['gt' => $data['value']];	
			} else if ($data['operator'] == '>='){
				$options[$data['field']] = ['gte' => $data['value']];	
			} else if ($data['operator'] == '<='){
				$options[$data['field']] = ['lte' => $data['value']];	
			} else if ($data['operator'] == '<'){
				$options[$data['field']] = ['lt' => $data['value']];	
			}
		}
		if($limit != 0){
			if($limit < $this->apiMaxLimit){
				$options['limit'] = $limit;	
			} else {
				$options['limit'] = $this->apiMaxLimit;
			}
		}
		if(is_object($pagination) && isset($pagination->ending_before)){
			$options['ending_before'] = $pagination->ending_before;
		} else if(is_object($pagination) && isset($pagination->starting_after)){
			$options['starting_after'] = $pagination->starting_after;	
		}
		return $options;
	}

	public function flattenAttributes($attributes)
	{
		$return_attributes = [];
		foreach($attributes as $key => $attribute){
			if(is_array($attribute)){
				$return_attributes[$key] = $this->flattenAttributes($attribute);
			} else if(is_object($attribute)){
				$return_attributes[$key] = $this->flattenAttributes($attribute->getAttributes());
			} else {
				$return_attributes[$key] = $attribute;
			}
		}
		return $return_attributes;
	}

}