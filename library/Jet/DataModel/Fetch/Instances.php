<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 *
 */
class DataModel_Fetch_Instances extends DataModel_Fetch implements Data_Paginator_DataSource, BaseObject_Interface_ArrayEmulator
{

	/**
	 * @var array|DataModel_PropertyFilter|null
	 */
	protected array|DataModel_PropertyFilter|null $load_filter = null;

	/**
	 * @var array|null|DataModel[]
	 */
	protected ?array $_instances = null;

	/**
	 * @var array
	 */
	protected array $_where = [];

	/**
	 * @return array|DataModel_PropertyFilter
	 */
	public function getLoadFilter(): array|DataModel_PropertyFilter
	{
		return $this->load_filter;
	}

	/**
	 * @param array|DataModel_PropertyFilter $load_filter
	 */
	public function setLoadFilter( array|DataModel_PropertyFilter $load_filter ): void
	{
		if( $load_filter ) {

			if( !($load_filter instanceof DataModel_PropertyFilter) ) {
				$load_filter = new DataModel_PropertyFilter(
					$this->data_model_definition, $load_filter
				);
			}
		}

		$this->load_filter = $load_filter;
	}

	/**
	 *
	 */
	protected function _fetch(): void
	{
		if( $this->data !== null ) {
			return;
		}

		$this->data = [];

		$ids = DataModel_Backend::get( $this->data_model_definition )->fetchAll( $this->query );

		$this->_where = [];
		foreach( $ids as $id_data ) {
			$id = clone $this->empty_id_instance;

			$id_where = [];
			foreach( $id->getPropertyNames() as $k ) {
				$id->setValue( $k, $id_data[$k] );

				if($id_where) {
					$id_where[] = 'AND';
				}
				$id_where[$k] = $id_data[$k];
			}

			if($this->_where) {
				$this->_where[] = 'OR';
			}
			$this->_where[] = $id_where;

			$id_str = (string)$id;

			$this->data[$id_str] = $id_str;
		}

		/**
		 * @var DataModel $class_name
		 */
		$class_name = $this->data_model_definition->getClassName();
		$model_name = $this->data_model_definition->getModelName();

		$this->_instances = $class_name::fetch(
			[
				$model_name => $this->_where
			],
			null,
			function( $item ) {
				/**
				 * @var DataModel $item
				 */
				return $item->getIDController()->toString();
			},
			$this->load_filter
		);

	}

	/**
	 * @return array
	 */
	public function toArray(): array
	{
		$result = [];

		foreach( $this as $key => $val ) {
			$result[$key] = $val->jsonSerialize();
		}

		return $result;
	}

	/**
	 * @param mixed $item
	 *
	 * @return DataModel|DataModel_Related_1toN|DataModel_Related_1to1
	 */
	protected function _get( mixed $item ): DataModel|DataModel_Related_1toN|DataModel_Related_1to1
	{
		return $this->_instances[$item];
	}

}