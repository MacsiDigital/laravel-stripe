<?php

namespace MacsiDigital\Stripe\Support;

use Illuminate\Support\Collection;

class Query
{
	protected $model;

	protected $where = [];

	protected $limit = '';

	protected $ending_before = '';
	protected $starting_after = '';

	protected $results = [];

	public function __construct($model)
	{
		$this->model = $model;
	}

	public function where($field, $operator, $value="") 
	{
		if($value == ''){
			$value = $operator;
			$operator = '=';
		}
		$this->where[$field] = ['operator' => $operator, 'value' => $value];
		return $this;
	}

	public function endingBefore($pointer) 
	{
		$this->ending_before = $pointer;
	}

	public function startingAfter($pointer) 
	{
		$this->starting_after = $pointer;
	}

	public function processOptions($where = []) 
	{
		$options = [];
		foreach($where as $key => $data){
			if($data['operator'] == '='){
				$options[$key] = $data['value'];	
			} else if ($data['operator'] == '>'){
				$options[$key] = ['gt' => $data['value']];	
			} else if ($data['operator'] == '>='){
				$options[$key] = ['gte' => $data['value']];	
			} else if ($data['operator'] == '<='){
				$options[$key] = ['lte' => $data['value']];	
			} else if ($data['operator'] == '<'){
				$options[$key] = ['lt' => $data['value']];	
			}
		}
		if($this->limit != ''){
			$options['limit'] = $this->limit;
		}
		if($this->ending_before != ''){
			$options['ending_before'] = $this->ending_before;	
		} else if($this->starting_after != ''){
			$options['starting_after'] = $this->starting_after;	
		}
		return $options;
	}

	public function all() 
	{
		if($this->results != []){
			return $this->results;
		}
		$object = $this->model->stripeModelClass;
		return $this->collect($object::all());
	}

	public function get() 
	{
		if($this->results != []){
			return $this->results;
		}
		$object = $this->model->stripeModelClass;
		return $this->collect($object::all($this->processOptions($this->where)));
	}

	public function first() 
	{
		$this->limit(1);
		return $this->get()[0];
	}

	public function find($id) 
	{
		if(is_array($id)){
			$return_array = [];
			foreach($id as $find_id){
				$return_array[] = $this->find($id);
			}
			return $this->collect($return_data);
		} else {
			$object = $this->model->stripeModelClass;
			return $this->hydrate($object::retrieve($id));
		}
	}

	public function limit(int $amount) 
	{
		$this->amount = $amount;
	}

	public function page() 
	{
		
	}

	public function order() 
	{
		
	}

	public function value()
	{

	}

	public function count() 
	{
	 	$this->results = $this->get();
	 	return count($this->results);
	}

	public function max() 
	{
	 	
	}

	public function min() 
	{
	 	
	}

	public function avg() 
	{
	 	
	}

	public function sum() 
	{
	 	
	}

	public function exists() 
	{
		
	}

	public function doesntExist() 
	{
		
	}

	public function collect($response) 
	{
		$return_data = [];
		foreach($response['data'] as $object){
			$return_data[] = $this->hydrate($object);
		}
		return collect($return_data);
	}

	public function hydrate($object){
		$model = get_class($this->model);
		$model = new $model;
		$model->hydrate($object->jsonSerialize());
		$model->stripeObject = $object;
		return $model;
	}
}