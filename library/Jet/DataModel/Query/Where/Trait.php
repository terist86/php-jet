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
 * @subpackage DataModel_Query
 */
namespace Jet;

trait DataModel_Query_Where_Trait {

	/**
	 * @var DataModel_Query
	 */
	protected $query;


	/**
	 * @param string|array $val
	 *
	 * @throws DataModel_Query_Exception
	 */
	protected function _determineLogicalOperatorOrSubExpressions($val) {
		if(is_array($val)) {
			/** @noinspection PhpUndefinedMethodInspection */
			/** @noinspection PhpParamsInspection */
			$this->addSubExpressions( new self( $this->query, $val ) );
		} else {
			switch( strtoupper($val) ) {
				case DataModel_Query::L_O_AND:
					/** @noinspection PhpUndefinedMethodInspection */
					$this->addAND();
					break;
				case DataModel_Query::L_O_OR:
					/** @noinspection PhpUndefinedMethodInspection */
					$this->addOR();
					break;
				default:
					throw new DataModel_Query_Exception(
						'Unknown logical operator \''.strtoupper($val).'\'. Available operators: AND, OR',
						DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
					);
					break;
			}

		}

	}

	/**
	 * @param &$key
	 *
	 * @return string
	 */
	protected function _determineOperator(&$key) {
		$operator = DataModel_Query::O_EQUAL;
		$key = trim( $key );

		foreach( DataModel_Query::$_available_operators as $s_operator ) {
			$len = strlen($s_operator);
			if(substr($key, -$len)==$s_operator) {
				$operator = $s_operator;
				$key = trim(substr($key, 0, -$len));
				break;
			}
		}

		return $operator;
	}

}