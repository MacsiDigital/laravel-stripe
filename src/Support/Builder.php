<?php

namespace MacsiDigital\Stripe\Support;

use Closure;
use Exception;
use BadMethodCallException;
use MacsiDigital\Stripe\Support\API;
use MacsiDigital\Stripe\Support\Model;
use MacsiDigital\Stripe\Contracts\Builder as Contract;
use MacsiDigital\Stripe\Contracts\StripeAPI;
use MacsiDigital\Stripe\Traits\BuildsQueries;
use MacsiDigital\Stripe\Traits\ForwardsCalls;
use MacsiDigital\Stripe\Exceptions\InvalidInterfaceException;

class Builder implements Contract
{
	use BuildsQueries, ForwardsCalls;

	/**
     * The model being queried.
     *
     * @var \MacsiDigital\Support\Model
     */
    protected $model;

    /**
     * The connection being queried.
     *
     * @var \MacsiDigital\Support\Model
     */
    protected $connection;

    /**
     * The columns that should be returned.
     *
     * @var array
     */
    public $columns;

    /**
     * The where constraints for the query.
     *
     * @var array
     */
    public $wheres = [];

    /**
     * The maximum number of records to return.
     *
     * @var int
     */
    public $limit;

    /**
     * The orderings for the query.
     *
     * @var array
     */
    public $orders;

    /**
     * The number of records to skip.
     *
     * @var int
     */
    public $offset;

    /**
     * All of the available clause operators.
     *
     * @var array
     */
    public $operators = [
        '=', '<', '>', '<=', '>=', '<>', '!=', '<=>',
        'like', 'like binary', 'not like', 'ilike',
        '&', '|', '^', '<<', '>>',
        'rlike', 'regexp', 'not regexp',
        '~', '~*', '!~', '!~*', 'similar to',
        'not similar to', 'not ilike', '~~*', '!~~*',
    ];

    public function __construct(StripeAPI $connection) 
    {
    	$this->setConnection($connection);
    }

    protected function setConnection($connection) 
    {
    	$this->connection = $connection;
    }

    /**
     * Create and return an un-saved model instance.
     *
     * @param  array  $attributes
     * @return \MacsiDigital\Support\Model
     */
    public function make(array $attributes = [])
    {
        return $this->newModelInstance($attributes);
    }

    /**
     * Add a basic where clause to the query.
     *
     * @param  string|array|\Closure  $column
     * @param  mixed   $operator
     * @param  mixed   $value
     * @param  string  $boolean
     * @return $this
     */
    public function where($field, $operator = null, $value = null, $boolean = 'and')
    {
        
        // If the column is an array, we will assume it is an array of key-value pairs
        // and can add them each as a where clause. We will maintain the boolean we
        // received when the method was called and pass it into the nested where.
        if (is_array($field)) {
            return $this->addArrayOfWheres($field, $boolean);
        }

        // Here we will make some assumptions about the operator. If only 2 values are
        // passed to the method, we will assume that the operator is an equals sign
        // and keep going. Otherwise, we'll require the operator to be passed in.
        [$value, $operator] = $this->prepareValueAndOperator(
            $value, $operator, func_num_args() === 2
        );

        // If the given operator is not found in the list of valid operators we will
        // assume that the developer is just short-cutting the '=' operators and
        // we will set the operators to '=' and set the values appropriately.
        if ($this->invalidOperator($operator)) {
            [$value, $operator] = [$operator, '='];
        }

        // Now that we are working with just a simple query we can put the elements
        // in our array and add the query binding to our array of bindings that
        // will be bound to each SQL statements when it is finally executed.
        $this->wheres[] = compact(
            'field', 'operator', 'value', 'boolean'
        );

        return $this;
    }

    /**
     * Add an array of where clauses to the query.
     *
     * @param  array  $column
     * @param  string  $boolean
     * @param  string  $method
     * @return $this
     */
    protected function addArrayOfWheres($column, $boolean, $method = 'where')
    {
        return $this->whereNested(function ($query) use ($column, $method, $boolean) {
            foreach ($column as $key => $value) {
                if (is_numeric($key) && is_array($value)) {
                    $query->{$method}(...array_values($value));
                } else {
                    $query->$method($key, '=', $value, $boolean);
                }
            }
        }, $boolean);
    }

    /**
     * Prepare the value and operator for a where clause.
     *
     * @param  string  $value
     * @param  string  $operator
     * @param  bool  $useDefault
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public function prepareValueAndOperator($value, $operator, $useDefault = false)
    {
        if ($useDefault) {
            return [$operator, '='];
        } elseif ($this->invalidOperatorAndValue($operator, $value)) {
            throw new InvalidArgumentException('Illegal operator and value combination.');
        }

        return [$value, $operator];
    }

    /**
     * Determine if the given operator and value combination is legal.
     *
     * Prevents using Null values with invalid operators.
     *
     * @param  string  $operator
     * @param  mixed  $value
     * @return bool
     */
    protected function invalidOperatorAndValue($operator, $value)
    {
        return is_null($value) && in_array($operator, $this->operators) &&
             ! in_array($operator, ['=', '<>', '!=']);
    }

    /**
     * Determine if the given operator is supported.
     *
     * @param  string  $operator
     * @return bool
     */
    protected function invalidOperator($operator)
    {
        return ! in_array(strtolower($operator), $this->operators, true) &&
               ! in_array(strtolower($operator), $this->grammar->getOperators(), true);
    }

     /**
     * Add an "order by" clause to the query.
     *
     * @param  string  $column
     * @param  string  $direction
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function orderBy($column, $direction = 'asc')
    {
        $direction = strtolower($direction);

        if (! in_array($direction, ['asc', 'desc'], true)) {
            throw new InvalidArgumentException('Order direction must be "asc" or "desc".');
        }

        $this->orders[] = [
            'column' => $column,
            'direction' => $direction,
        ];

        return $this;
    }

    /**
     * Add a descending "order by" clause to the query.
     *
     * @param  string  $column
     * @return $this
     */
    public function orderByDesc($column)
    {
        return $this->orderBy($column, 'desc');
    }

     /**
     * Add an "order by" clause for a timestamp to the query.
     *
     * @param  string  $column
     * @return $this
     */
    public function latest($column = null)
    {
        if (is_null($column)) {
            $column = $this->model->getCreatedAtColumn() ?? 'created';
        }

        return $this->orderBy($column, 'desc');
    }

    /**
     * Add an "order by" clause for a timestamp to the query.
     *
     * @param  string  $column
     * @return $this
     */
    public function oldest($column = null)
    {
        if (is_null($column)) {
            $column = $this->model->getCreatedAtColumn() ?? 'created';
        }

         return $this->orderBy($column, 'asc');

        return $this;
    }

    /**
     * Create a collection of models from plain arrays.
     *
     * @param  array  $items
     * @return \MacsiDigital\Support\Collection
     */
    public function hydrate(array $items)
    {
        $instance = $this->newModelInstance();

        return $instance->newCollection(array_map(function ($item) use ($instance) {
            return $instance->newFromBuilder($item);
        }, $items));
    }

    /**
     * Find a model by its primary key.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return \MacsiDigital\Support\Model|\MacsiDigital\Support\Collection|static[]|static|null
     */
    public function find($id, $columns = ['*'])
    {
    	if($this->model->cantPerform('find')){
			throw new Exception(get_class($this->model).' cannot perform find method');
		}
        return $this->newModelInstance($this->connection->find($id));
    }

    public function refresh() 
    {
        return  $this->connection->find($this->model->getKey());
    }

    /**
     * Get the first record matching the attributes or instantiate it.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return \MacsiDigital\Support\Model|static
     */
    public function firstOrNew(array $attributes, array $values = [])
    {
        if (! is_null($instance = $this->where($attributes)->first())) {
            return $instance;
        }

        return $this->newModelInstance($attributes + $values);
    }

    /**
     * Get the first record matching the attributes or create it.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return \MacsiDigital\Support\Model|static
     */
    public function firstOrCreate(array $attributes, array $values = [])
    {
        if (! is_null($instance = $this->where($attributes)->first())) {
            return $instance;
        }

        return tap($this->newModelInstance($attributes + $values), function ($instance) {
            $instance->save();
        });
    }

    /**
     * Create or update a record matching the attributes, and fill it with values.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return \MacsiDigital\Support\Model|static
     */
    public function updateOrCreate(array $attributes, array $values = [])
    {
        return tap($this->firstOrNew($attributes), function ($instance) use ($values) {
            $instance->fill($values)->save();
        });
    }

    /**
     * Execute the query as a "select" statement.
     *
     * @param  array  $columns
     * @return \MacsiDigital\Support\Collection|static[]
     */
    public function get($columns = ['*'])
    {
    	if($this->model->cantPerform('get')){
			throw new Exception(get_class($this->model).' cannot perform get method');
		}
        return $this->getModel()->newCollection($this->getModels());
    }

    /**
     * Get the hydrated models without eager loading.
     *
     * @param  array  $columns
     * @return \MacsiDigital\Support\Model[]|static[]
     */
    public function getModels($columns = ['*'])
    {
        return $this->model->hydrate(
            $this->connection->get($this->wheres, $this->limit, $columns)->all()
        )->all();
    }

    /**
     * Save a new model and return the instance.
     *
     * @param  array  $attributes
     * @return \MacsiDigital\Support\Model|$this
     */
    public function create(array $attributes = [])
    {
        return tap($this->newModelInstance($attributes), function ($instance) {
            $instance->save();
        });
    }

     /**
     * Insert a new record
     *
     * @param  array  $values
     * @param  string|null  $sequence
     * @return int
     */
    public function insert(array $attributes)
    {
    	if($this->model->cantPerform('insert')){
			throw new Exception(get_class($this->model).' cannot perform insert method');
		}
    	return $this->connection->insert($attributes);
    }

    /**
     * Insert a new record
     *
     * @param  array  $values
     * @param  string|null  $sequence
     * @return int
     */
    public function update($id, array $attributes)
    {
    	if($this->model->cantPerform('update')){
			throw new Exception(get_class($this->model).' cannot perform update method');
		}
    	if($id == null || $attributes == []){
    		return;
    	}
    	
    	return $this->connection->update($id, $attributes);
    }

    /**
     * Delete a record from the database.
     *
     * @return mixed
     */
    public function delete($id)
    {
    	if($this->model->cantPerform('delete')){
			throw Exception(get_class($this->model.' cannot perform delete method'));
		}
    	if($id == null){
    		return;
    	}
        return $this->connection->delete($id);
    }

    /**
     * Insert or update a record matching the attributes, and fill it with values.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return bool
     */
    public function updateOrInsert(array $attributes, array $values = [])
    {
        if (! $this->where($attributes)->exists()) {
            return $this->insert(array_merge($attributes, $values));
        }

        if (empty($values)) {
            return true;
        }

        return (bool) $this->take(1)->update($values);
    }

    /**
     * Create a new instance of the model being queried.
     *
     * @param  array  $attributes
     * @return \MacsiDigital\Support\Model|static
     */
    public function newModelInstance($attributes = [])
    {
        return $this->model->newInstance($attributes);
    }

    /**
     * Get the model instance being queried.
     *
     * @return \MacsiDigital\Support\Model|static
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set a model instance for the model being queried.
     *
     * @param  \MacsiDigital\Support\Model  $model
     * @return $this
     */
    public function setModel(Model $model)
    {
        $this->model = $model;

        $this->connection->setModel($model);

        return $this;
    }

    /**
     * Dynamically handle calls into the query instance.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (method_exists($this->model, $scope = 'scope'.ucfirst($method))) {
            return $this->callScope([$this->model, $scope], $parameters);
        }

        static::throwBadMethodCallException($method);
    }

    /**
     * Dynamically handle calls into the query instance.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public static function __callStatic($method, $parameters)
    {
        return (new self)->$method(... $parameters);
    }

    /**
     * Alias to set the "offset" value of the query.
     *
     * @param  int  $value
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function skip($value)
    {
        return $this->offset($value);
    }

    /**
     * Set the "offset" value of the query.
     *
     * @param  int  $value
     * @return $this
     */
    public function offset($value)
    {
        $this->offset = max(0, $value);

        return $this;
    }

    /**
     * Alias to set the "limit" value of the query.
     *
     * @param  int  $value
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function take($value)
    {
        return $this->limit($value);
    }

    /**
     * Set the "limit" value of the query.
     *
     * @param  int  $value
     * @return $this
     */
    public function limit($value)
    {
        if ($value >= 0) {
            $this->limit = $value;
        }

        return $this;
    }

    

    /**
     * Get an array with the values of a given column.
     *
     * @param  string  $column
     * @param  string|null  $key
     * @return \Illuminate\Support\Collection
     */
    public function pluck($column, $key = null)
    {
        // First, we will need to select the results of the query accounting for the
        // given columns / key. Once we have the results, we will be able to take
        // the results and get the exact data that was requested for the query.
        $queryResult = $this->onceWithColumns(
            is_null($key) ? [$column] : [$column, $key],
            function () {
                return $this->processor->processSelect(
                    $this, $this->runSelect()
                );
            }
        );

        if (empty($queryResult)) {
            return collect();
        }

        // If the columns are qualified with a table or have an alias, we cannot use
        // those directly in the "pluck" operations since the results from the DB
        // are only keyed by the column itself. We'll strip the table out here.
        $column = $this->stripTableForPluck($column);

        $key = $this->stripTableForPluck($key);

        return is_array($queryResult[0])
                    ? $this->pluckFromArrayColumn($queryResult, $column, $key)
                    : $this->pluckFromObjectColumn($queryResult, $column, $key);
    }

    /**
     * Determine if any rows exist for the current query.
     *
     * @return bool
     */
    public function exists()
    {
        $results = $this->connection->select(
            $this->grammar->compileExists($this), $this->getBindings(), ! $this->useWritePdo
        );

        // If the results has rows, we will get the row and see if the exists column is a
        // boolean true. If there is no results for this query we will return false as
        // there are no rows for this query at all and we can return that info here.
        if (isset($results[0])) {
            $results = (array) $results[0];

            return (bool) $results['exists'];
        }

        return false;
    }

    /**
     * Determine if no rows exist for the current query.
     *
     * @return bool
     */
    public function doesntExist()
    {
        return ! $this->exists();
    }

    /**
     * Retrieve the "count" result of the query.
     *
     * @param  string  $columns
     * @return int
     */
    public function count($columns = '*')
    {
        // COunt Records
    }

     /**
     * Get a new instance of the query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function newQuery()
    {
        return app(self::class);
    }

   
}