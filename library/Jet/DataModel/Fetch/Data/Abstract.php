<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Fetch
 */
namespace Jet;

abstract class DataModel_Fetch_Data_Abstract extends DataModel_Fetch_Abstract implements Data_Paginator_DataSource_Interface,\ArrayAccess, \Iterator, \Countable,Object_Serializable_REST  {
	/**
	 * @var string
	 */
	protected $backend_fetch_method = '';

	/**
	 * @var array
	 */
	protected $data = null;

	/**
	 * @var callable
	 */
	protected $array_walk_callback;


	/**
	 *
	 * @param string[] $select_items
	 * @param array|DataModel_Query $query
	 * @param DataModel_Definition_Model_Abstract $data_model_definition
	 *
	 * @throws DataModel_Query_Exception
	 */
	final public function __construct( array $select_items, $query, DataModel_Definition_Model_Abstract $data_model_definition  ) {
		parent::__construct( $query, $data_model_definition );

		$this->query->setSelect( $select_items );
	}

	/**
	 *
	 * @param callable $array_walk_callback
	 */
	public function setArrayWalkCallback( callable $array_walk_callback) {
		$this->array_walk_callback = $array_walk_callback;
	}



	/**
	 * @return array
	 */
	public function toArray() {
		$result = array();

		foreach($this as $key=>$val) {
			$result[$key] = $val;
		}

		return $result;
	}

	/**
	 * @return array
	 */
	public function jsonSerialize() {
		$result = array();

		foreach($this as $key=>$val) {
			foreach($val as $k=>$v) {
				if(is_object($v)) {
					$val[$k] = (string)$v;
				}
			}
			$result[$key] = $val;
		}

		return $result;
	}

	/**
	 * @return string
	 */
	public function toXML() {
		$model_name = $this->data_model_definition->getModelName();

		$result = '';
		$result .= '<list model_name="'.$model_name.'">'.JET_EOL;

		foreach($this->jsonSerialize() as $val) {
			$result .= JET_TAB.'<item>'.JET_EOL;
			foreach($val as $k=>$v) {
				$result .= JET_TAB.JET_TAB.'<'.$k.'>'.htmlspecialchars($v).'</'.$k.'>'.JET_EOL;
			}
			$result .= JET_TAB.'</item>'.JET_EOL;

		}

		$result .= '</list>'.JET_EOL;

		return $result;
	}

	/**
	 * @return string
	 */
	public function toJSON() {
		$this->_fetch();

		return json_encode( $this->jsonSerialize() );
	}


	/**
	 * Fetches data
	 *
	 */
	public function _fetch() {

		if($this->data!==null) {
			return;
		}

		$this->data = $this->data_model_definition->getBackendInstance()->{$this->backend_fetch_method}( $this->query );
	}

//--------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------

	/**
	 * @see \Countable
	 *
	 * @return int
	 */
	public function count() {
		$this->_fetch();
		return count($this->data);
	}

	/**
	 * @see \ArrayAccess
	 * @param int $offset
	 * @return bool
	 */
	public function offsetExists( $offset  ) {
		$this->_fetch();
		return array_key_exists($offset, $this->data);
	}

	/**
	 * @see \ArrayAccess
	 * @param int $offset
	 *
	 * @return DataModel
	 */
	public function offsetGet( $offset ) {
		$this->_fetch();

		$item = $this->data[$offset];
		if( $this->array_walk_callback ) {
			$callback = $this->array_walk_callback;

			$callback( $item );
		}

		return $item;
	}

	/**
	 *
	 * @see \ArrayAccess
	 * @param int $offset
	 * @param mixed $value
	 */
	public function offsetSet( $offset , $value ) {
		$this->data[$offset] = $value;
	}

	/**
	 * @see \ArrayAccess
	 * @param int $offset
	 */
	public function offsetUnset( $offset )	{
		$this->_fetch();
		unset( $this->data[$offset] );
	}

	/**
	 * @see \Iterator
	 *
	 * @return DataModel
	 */
	public function current() {
		$this->_fetch();

		$item = current($this->data);

		if( $this->array_walk_callback ) {
			$callback = $this->array_walk_callback;

			$callback( $item );
		}

		return $item;
	}
	/**
	 * @see \Iterator
	 * @return string
	 */
	public function key() {
		$this->_fetch();
		return key($this->data);
	}
	/**
	 * @see \Iterator
	 */
	public function next() {
		$this->_fetch();
		return next($this->data);
	}
	/**
	 * @see \Iterator
	 */
	public function rewind() {
		$this->_fetch();
		reset($this->data);
	}
	/**
	 * @see \Iterator
	 * @return bool
	 */
	public function valid()	{
		$this->_fetch();
		return key($this->data)!==null;
	}

}