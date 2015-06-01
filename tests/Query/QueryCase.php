<?php namespace ClanCats\Hydrahon\Test;
/**
 * Hydrahon builder test 
 ** 
 *
 * @package 		Hydrahon
 * @copyright 		Mario Döring
 */

use ClanCats\Hydrahon\BaseQuery;

abstract class Query_QueryCase extends \PHPUnit_Framework_TestCase
{
	protected $queryClass;

	/**
	 * Returns an new query object
	 * 
	 * @param mixed 		$results
	 * 
	 * @return ClanCats\Hydrahon\Query\Sql\BaseQuery
	 */
	protected function createQuery($result = null)
	{
		return new $this->queryClass(function( $query ) use( $result ) {
			return $result;
		} , 'phpunit', 'db_phpunit' );
	}

	/**
	 * Returns all attributes or a specific one
	 * 
	 * @param ClanCats\Hydrahon\Query\Sql\BaseQuery 		$query
	 * @param string 										$key
	 * @return mixed
	 */
	protected function attributes(BaseQuery $query, $key = null)
	{
		$attributes = array_filter($query->attributes());

		foreach($attributes as $queryKey => &$value)
		{
			if ($queryKey === 'wheres' && is_array($value))
			{
				foreach($value as &$where)
				{
					if (isset($where[1]) && $where[1] instanceof BaseQuery)
					{
						$where[1] = $this->attributes($where[1]);
					}
				}
			}
		}

		if (!is_null($key))
		{
			if (!isset($attributes[$key]))
			{
				return null;
			}

			return $attributes[$key];
		}

		return $attributes;
	}

	/**
	 * Asserts the attributes of the given query
	 * 
	 * @param ClanCats\Hydrahon\Query\Sql\BaseQuery 		$query
	 * @param array 										$attributes
	 * @return void
	 */
	protected function assertAttributes(BaseQuery $query, array $attributes = array())
	{
		$attributes = array_merge(array(
			'database' => 'db_phpunit',
			'table' => 'phpunit'
		), $attributes);

		$this->assertEquals($attributes, $this->attributes($query));
	}
}