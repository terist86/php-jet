<?php
/**
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package DataModel
 */
namespace Jet;

/**
 * //TODO: update comments
 *
 *	Common options
 *		'type':
 *		'default_value':
 *		'backend_options':
 *              'is_ID',
 *              'do_not_serialize':
 *              'description':
 *
 *      Form options:
 *              'form_field_type':
 *              'form_field_label':
 *              'form_field_options':
 *              'form_field_error_messages':
 *              'form_field_get_default_value_callback':
 *              'form_field_get_select_options_callback':
 *
 *	Data validation options
 *		All types:
 *			'validation_method':
 *			'list_of_valid_options':
 *			'error_messages':
 *
 *		TYPE_STRING:
 *			'is_required':
 *			'max_len':
 *			'validation_regexp':
 *
 *		TYPE_INT,TYPE_FLOAT:
 *			'min_value':
 *			'max_value':
 *
 *
 *	Type specific options
 *		TYPE_DATA_MODEL:
 *			'data_model_class'
 *		TYPE_ARRAY
 *			'item_type':
 *
 *
 * Relations to another (indenpendent) model.
 *
 * Example:
 *
 * JetDataModel:relation = [ 'Some\RelatedClass', [ 'this.class_property_name'=>'related_class_property_name', 'this.another_class_property_name' => 'another_related_class_property_name', 'this_value.getValueMethodName' => 'another_related_class_property' ], Jet\DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN ]
 *
 *
 * Then you can use relation in query like this:
 *
 * $query = array(
 *          'relation_name.some_related_class_property' => 'value',
 *          'AND',
 *          'relation_name.another_some_related_class_property!' => 1234
 * );
 *
 * Warning!
 *
 * Outer relation has no affect on saving or deleting object (like DataModel_Related_* models has).
 *
 *
 * @var array
 */



/**
 * Class DataModel
 *
 * @JetDataModel:ID_class_name = 'Jet\\DataModel_ID_Default'
 */
abstract class DataModel extends Object implements Object_Serializable_REST, Object_Reflection_ParserInterface {
	const DEFAULT_ID_PROPERTY_NAME = 'ID';

	const TYPE_ID = 'ID';
	const TYPE_STRING = 'String';
	const TYPE_BOOL = 'Bool';
	const TYPE_INT = 'Int';
	const TYPE_FLOAT = 'Float';
	const TYPE_LOCALE = 'Locale';
	const TYPE_DATE = 'Date';
	const TYPE_DATE_TIME = 'DateTime';
	const TYPE_ARRAY = 'Array';
	const TYPE_DATA_MODEL = 'DataModel';

	const KEY_TYPE_PRIMARY = 'PRIMARY';
	const KEY_TYPE_INDEX = 'INDEX';
	const KEY_TYPE_UNIQUE = 'UNIQUE';

	/**
	 * @var DataModel_ID_Abstract
	 */
	private $__ID;

	/**
	 * @var bool
	 */
	private $___data_model_saved = false;

	/**
	 * @var bool
	 */
	private $___data_model_ready_to_save = false;


	/**
	 *
	 * @var DataModel_History_Backend_Abstract
	 */
	private $___data_model_history_backend_instance = null;

	/**
	 *
	 * @var DataModel_Validation_Error[]
	 */
	private $___data_model_data_validation_errors = array();


	public function __construct() {
		$this->initNewObject();
	}

	/**
	 * Initializes new DataModel
	 *
	 */
	protected function initNewObject() {

		$this->___data_model_ready_to_save = false;
		$this->___data_model_saved = false;


		foreach( $this->getDataModelDefinition()->getProperties() as $property_name => $property_definition ) {
			if($property_definition->getIsDataModel()) {
				$default_value = $property_definition->getDefaultValue( $this );

				$this->{$property_name} = $default_value;

			} else {
				if(!$this->{$property_name}) {
					$default_value = $property_definition->getDefaultValue( $this );

					$this->{$property_name} = $default_value;

					$property_definition->checkValueType( $this->{$property_name} );
				}
			}


		}

		$this->generateID();
	}


	/**
	 * Returns ID
	 *
	 * @return DataModel_ID_Abstract
	 */
	public function getID() {
		if(!$this->__ID) {
			$this->__ID = $this->getEmptyIDInstance();
		}

		foreach($this->__ID as $property_name => $value) {
			$this->__ID[$property_name] = $this->{$property_name};
		}

		return $this->__ID;
	}


	/**
	 * @return DataModel_ID_Abstract
	 */
	public static function getEmptyIDInstance() {
		return static::getDataModelDefinition()->getEmptyIDInstance();
	}

	/**
	 * @param string $ID
	 *
	 * @return DataModel_ID_Abstract
	 */
	public static function createID( $ID ) {
		return static::getEmptyIDInstance()->createID( $ID );
	}


	/**
	 * @return DataModel_ID_Abstract
	 */
	public function resetID() {
		if(!$this->__ID) {
			$this->__ID = $this->getEmptyIDInstance();
		}

		$this->__ID->reset();

		foreach( $this->__ID as $property_name=>$value ) {
			$this->{$property_name} = $value;
		}

		return $this->__ID;

	}



	/**
	 * Generate unique ID
	 *
	 * @param bool $called_after_save (optional, default = false)
	 * @param mixed $backend_save_result  (optional, default = null)
	 *
	 * @throws DataModel_Exception
	 */
	protected function generateID(  $called_after_save = false, $backend_save_result = null  ) {

		$ID = $this->getID();

		$ID->generate( $this, $called_after_save, $backend_save_result );

		foreach( $ID as $property_name=>$value ) {
			$this->{$property_name} = $value;
		}
	}


	/**
	 * Returns true if the model instance is new (was not saved yet)
	 *
	 * @return bool
	 */
	public function getIsNew() {
		return !$this->___data_model_saved;
	}

	/**
	 *
	 */
	protected function setIsNew() {
		$this->___data_model_saved = false;
	}

	/**
	 * @return bool
	 */
	public function getIsSaved() {
		return $this->___data_model_saved;
	}

	/**
	 *
	 */
	public function setIsSaved() {
		$this->___data_model_saved = true;
	}


	/**
	 * @param string $property_name
	 * @param mixed &$value
	 * @param bool $throw_exception (optional, default: true)
	 *
	 * @throws DataModel_Exception
	 * @throws DataModel_Validation_Exception
	 *
	 * @return bool
	 */
	public function validatePropertyValue( $property_name,&$value, $throw_exception=true ) {
		$properties = $this->getDataModelDefinition()->getProperties();
		if( !isset($properties[$property_name]) ) {
			throw new DataModel_Exception(
				'Unknown property \''.$property_name.'\'',
				DataModel_Exception::CODE_UNKNOWN_PROPERTY
			);
		}

		$property_definition = $properties[$property_name];

		$validation_method_name = $property_definition->getValidationMethodName();

		$errors = array();

		if($validation_method_name) {
			$this->{$validation_method_name}($property_definition, $value, $errors);
		} else {
			$property_definition->validateProperties($value, $errors);
		}

		if($errors) {
			if($throw_exception) {
				throw new DataModel_Validation_Exception( $this, $property_definition, $errors );
			} else {
				return false;
			}
		}

		return true;
	}

	/**
	 * @param string $property_name
	 * @param mixed &$value
	 *
	 * @throws DataModel_Validation_Exception
	 */
	protected function _setPropertyValue( $property_name, &$value ) {
		$this->validatePropertyValue( $property_name, $value );

		$this->{$property_name} = $value;
	}


	/**
	 * Validates data and returns true if everything is OK and ready to save
	 *
	 * @throws DataModel_Exception
	 * @return bool
	 */
	public function validateProperties() {

		$this->___data_model_data_validation_errors = array();

		$this->___data_model_ready_to_save = false;

		foreach( $this->getDataModelDefinition()->getProperties()  as $property_name=>$property_definition ) {
			if(
				$property_definition->getIsDataModel() &&
				$this->{$property_name}
			) {
				if(!is_object($this->{$property_name})) {

					throw new DataModel_Exception(
						get_class($this).'::'.$property_name.' should be an Object! ',
						DataModel_Exception::CODE_INVALID_PROPERTY_TYPE
					);
				}

				/**
				 * @var DataModel $prop
				 */
				$prop = $this->{$property_name};

				$prop->validateProperties();

				$this->___data_model_data_validation_errors = array_merge(
						$this->___data_model_data_validation_errors,
						$prop->getValidationErrors()
					);

				continue;
			}

			$validation_method_name = $property_definition->getValidationMethodName();

			if($validation_method_name) {
				$this->{$validation_method_name}($property_definition, $this->{$property_name}, $this->___data_model_data_validation_errors);
			} else {
				$property_definition->validateProperties($this->{$property_name}, $this->___data_model_data_validation_errors);
			}
		}

		if(count($this->___data_model_data_validation_errors)) {
			return false;
		}

		$this->___data_model_ready_to_save = true;

		return true;
	}

	/**
	 *
	 * @return DataModel_Validation_Error[]
	 */
	public function getValidationErrors() {
		return $this->___data_model_data_validation_errors;
	}



	/**
	 * Returns model definition
	 *
	 * @param string $class_name (optional)
	 *
	 * @return DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_Abstract|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN|DataModel_Definition_Model_Related_MtoN
	 */
	public static function getDataModelDefinition( $class_name='' )  {
		if(!$class_name) {
			$class_name = get_called_class();
		}

		return DataModel_Definition_Model_Abstract::getDataModelDefinition( $class_name );
	}


	/**
	 * @param $data_model_class_name
	 *
	 * @return DataModel_Definition_Model_Main
	 */
	public static function _getDataModelDefinitionInstance( $data_model_class_name ) {
		return new DataModel_Definition_Model_Main( $data_model_class_name );
	}

	/**
	 * Returns backend instance
	 *
	 * @return DataModel_Backend_Abstract
	 */
	public static function getBackendInstance() {
		return static::getDataModelDefinition()->getBackendInstance();
	}

	/**
	 *
	 * @return bool
	 */
	public static function getCacheEnabled() {
		return static::getDataModelDefinition()->getCacheEnabled();
	}

	/**
	 * Returns cache backend instance
	 *
	 * @return DataModel_Cache_Backend_Abstract
	 */
	public static function getCacheBackendInstance() {
		return static::getDataModelDefinition()->getCacheBackendInstance();
	}

	/**
	 *
	 * @return bool
	 */
	public static function getHistoryEnabled() {
		return static::getDataModelDefinition()->getHistoryEnabled();
	}

	/**
	 * Returns history backend instance
	 *
	 * @return DataModel_History_Backend_Abstract
	 */
	public function getHistoryBackendInstance() {
		$definition = static::getDataModelDefinition();

		if(!$definition->getHistoryEnabled()) {
			return false;
		}

		if(!$this->___data_model_history_backend_instance) {
			$this->___data_model_history_backend_instance = DataModel_Factory::getHistoryBackendInstance(
				$definition->getHistoryBackendType(),
				$definition->getHistoryBackendConfig()
			);

		}

		return $this->___data_model_history_backend_instance;
	}


	/**
	 * Loads DataModel.
	 *
	 * @param DataModel_ID_Abstract $ID
	 *
	 * @return \Jet\DataModel|mixed|null
	 *
	 * @throws DataModel_Exception
	 *
	 * @return DataModel
	 */
	public static function load( DataModel_ID_Abstract $ID ) {

		$definition = static::getDataModelDefinition();

		$cache = static::getCacheBackendInstance();


		$loaded_instance = null;
		if($cache) {
			$loaded_instance = $cache->get( $definition, $ID);

			if($loaded_instance) {
				foreach( $definition->getProperties() as $property_name=>$property_definition ) {
					if(!$property_definition->getIsDataModel()) {
						continue;
					}

					/**
					 * @var DataModel_Related_Abstract $related_object
					 */
					$related_object = $loaded_instance->{$property_name};

					$related_object->wakeUp( $loaded_instance );
				}

				return $loaded_instance;
			}
		}


		$query = $ID->getQuery();
		$query->setSelect( $definition->getProperties() );

		$dat = static::getBackendInstance()->fetchRow( $query );

		if(!$dat) {
			return null;
		}

		$loaded_instance = static::_load_dataToInstance( $dat );

		if($cache) {
			$cache->save($definition, $ID, $loaded_instance);
		}


		return $loaded_instance;

	}

	/**
	 * @param array $dat
	 * @param DataModel $main_model_instance
	 *
	 * @return DataModel
	 *
	 * @throws DataModel_Exception
	 */
	protected static function _load_dataToInstance( $dat, $main_model_instance=null ) {

		/**
		 * @var DataModel $loaded_instance
		 */
		$loaded_instance = new static();

		$definition = static::getDataModelDefinition();

		foreach( $definition->getProperties() as $property_name=>$property_definition ) {
			if($property_definition->getIsDataModel()) {
				continue;
			}
			$loaded_instance->$property_name = $dat[$property_name];
			$property_definition->checkValueType( $loaded_instance->$property_name );
		}


		foreach( $definition->getProperties() as $property_name=>$property_definition ) {
			if(!$property_definition->getIsDataModel()) {
				continue;
			}
			/**
			 * @var DataModel_Definition_Property_DataModel $property_definition
			 */
			$class_name = $property_definition->getDataModelClass();

			/**
			 * @var DataModel_Related_Abstract $related_instance
			 */
			$related_instance = Factory::getInstance( $class_name );

			if(
				!($related_instance instanceof DataModel_Related_Abstract) &&
				!($related_instance instanceof DataModel_Related_MtoN)
			) {
				throw new DataModel_Exception(
					'DataModel \''.get_class($related_instance).'\' is related class to  \''.get_class($loaded_instance).'\' but is not instance of  DataModel_Related*',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);
			}

			if($main_model_instance) {
				/**
				 * @var DataModel_Related_Abstract $loaded_instance
				 */
				$loaded_instance->{$property_name} = $related_instance->loadRelated( $main_model_instance, $loaded_instance );
			} else {
				/**
				 * @var DataModel $loaded_instance
				 */
				$loaded_instance->{$property_name} = $related_instance->loadRelated( $loaded_instance );
			}
		}


		$loaded_instance->setIsSaved();

		return $loaded_instance;
	}

	/**
	 * Save data.
	 * CAUTION: Call validateProperties first!
	 *
	 * @throws Exception
	 * @throws DataModel_Exception
	 */
	public function save() {

		$this->_checkBeforeSave();

		$cache = $this->getCacheBackendInstance();
		$backend = $this->getBackendInstance();

		if( !($this instanceof DataModel_Related_Abstract) ) {
			$backend->transactionStart();
		}


		if( $this->getIsNew() ) {
			$operation = 'save';
			$h_operation = DataModel_History_Backend_Abstract::OPERATION_SAVE;
		} else {
			$operation = 'update';
			$h_operation = DataModel_History_Backend_Abstract::OPERATION_UPDATE;
		}

		$this->___DataModelHistoryOperationStart( $h_operation );


		try {
			$this->{'_'.$operation}( $backend );
		} catch (Exception $e) {
			$backend->transactionRollback();
			throw $e;
		}

		if($cache) {
			$cache->{$operation}($this->getDataModelDefinition(), $this->getID(), $this);
		}

		if( !($this instanceof DataModel_Related_Abstract) ) {
			$backend->transactionCommit();
		}

		$this->___data_model_saved = true;

		$this->___DataModelHistoryOperationDone();

	}

	/**
	 * @throws DataModel_Exception
	 */
	protected function _checkBeforeSave() {
		if(!$this->___data_model_ready_to_save) {

			$errors = $this->getValidationErrors();
			foreach($errors as $i=>$e) {
				$errors[$i] = (string)$e;
			}

			if(!$errors) {
				$errors[] = 'none';
			}

			throw new DataModel_Exception(
				'Call '.get_class($this).'::validateProperties first! (Validation errors: '.implode(',', $errors).')',
				DataModel_Exception::CODE_SAVE_ERROR_VALIDATE_DATA_FIRST
			);
		}
	}

	/**
	 *
	 * @param DataModel_Backend_Abstract $backend
	 * @param DataModel $main_model_instance
	 */
	protected function _save( DataModel_Backend_Abstract $backend, DataModel $main_model_instance=null ) {
		$definition = $this->getDataModelDefinition();

		$record = new DataModel_RecordData( $definition );

		$related_model_properties = array();

		foreach( $definition->getProperties() as $property_name=>$property_definition ) {
			if( $property_definition->getIsDataModel() ) {
				$related_model_properties[$property_name]  = $property_definition;

				continue;
			}

			$record->addItem($property_definition, $this->{$property_name});
		}

		$this->generateID();

		$backend_result = $backend->save( $record );

		$this->generateID( true, $backend_result );

		if(!$main_model_instance) {
			foreach( $related_model_properties as $property_name=>$property_definition ) {
				if($this->{$property_name}!==null) {
					/**
					 * @var DataModel_Related_Abstract $prop
					 */
					$prop = $this->{$property_name};

					$prop->saveRelated( $this );
				}
			}
		} else {
			foreach( $related_model_properties as $property_name=>$property_definition ) {
				if($this->{$property_name}!==null) {
					/**
					 * @var DataModel_Related_Abstract $prop
					 */
					$prop = $this->{$property_name};

					/**
					 * @var DataModel_Related_Abstract $this
					 */
					$prop->saveRelated( $main_model_instance, $this );
				}
			}
		}
	}

	/**
	 *
	 * @param DataModel_Backend_Abstract $backend
	 * @param DataModel $main_model_instance
	 */
	protected function _update( DataModel_Backend_Abstract $backend, DataModel $main_model_instance=null ) {
		$definition = $this->getDataModelDefinition();

		$record = new DataModel_RecordData( $definition );

		$related_model_properties = array();

		foreach( $definition->getProperties() as $property_name=>$property_definition ) {
			if($property_definition->getIsID()) {
				continue;
			}

			if( $property_definition->getIsDataModel() ) {
				$related_model_properties[$property_name]  = $property_definition;

				continue;
			}

			$record->addItem($property_definition, $this->{$property_name});
		}

		$backend->update($record, $this->getID()->getQuery() );

		if(!$main_model_instance) {
			foreach( $related_model_properties as $property_name=>$property_definition ) {
				if($this->{$property_name}!==null) {
					/**
					 * @var DataModel_Related_Abstract $prop
					 */
					$prop = $this->{$property_name};

					$prop->saveRelated( $this );
				}
			}
		} else {
			foreach( $related_model_properties as $property_name=>$property_definition ) {
				if($this->{$property_name}!==null) {
					/**
					 * @var DataModel_Related_Abstract $prop
					 */
					$prop = $this->{$property_name};

					/**
					 * @var DataModel_Related_Abstract $this
					 */
					$prop->saveRelated( $main_model_instance, $this );
				}
			}

		}
	}


	/**
	 *
	 * @throws DataModel_Exception
	 */
	public function delete() {
		if( !$this->getID() || !$this->getIsSaved() ) {
			throw new DataModel_Exception('Nothing to delete... Object was not loaded.', DataModel_Exception::CODE_NOTHING_TO_DELETE);
		}

		$this->___DataModelHistoryOperationStart( DataModel_History_Backend_Abstract::OPERATION_DELETE );

		$backend = $this->getBackendInstance();
		$definition = $this->getDataModelDefinition();

		if( !($this instanceof DataModel_Related_Abstract) ) {
			$backend->transactionStart();
		}

		foreach( $definition->getProperties() as $property_name=>$property_definition ) {
			if(
				$property_definition->getIsDataModel() &&
				$this->{$property_name}
			) {
				/**
				 * @var DataModel $prop
				 */
				$prop = $this->{$property_name};

				$prop->delete();
			}
		}

		$backend->delete( $this->getID()->getQuery() );

		if( !($this instanceof DataModel_Related_Abstract) ) {
			$backend->transactionCommit();
		}

		$this->___DataModelHistoryOperationDone();

		$cache = $this->getCacheBackendInstance();
		if($cache) {
			$cache->delete( $definition, $this->getID() );
		}
 	}

	/**
	 * @param array $data
	 * @param array $where
	 */
	protected function updateData( array $data, array $where ) {
		$cache_enabled = $this->getCacheEnabled();

		$affected_IDs = null;
		if($cache_enabled) {
			$affected_IDs = $this->fetchObjectIDs($where);
		}

		$this->getBackendInstance()->update(
			DataModel_RecordData::createRecordData( $this,
				$data
			),
			DataModel_Query::createQuery( $this->getDataModelDefinition(),
				$where
			)
		);

		if($affected_IDs) {
			$cache = $this->getCacheBackendInstance();
			foreach($affected_IDs as $ID) {
				$cache->delete( $this->getDataModelDefinition(), $ID );
			}
		}
	}

	/**
	 * @param string $operation
	 */
	protected function ___DataModelHistoryOperationStart( $operation ) {
		$backend = $this->getHistoryBackendInstance();

		if( !$backend ) {
			return;
		}

		$backend->operationStart( $this, $operation );
	}

	/**
	 *
	 */
	protected function ___DataModelHistoryOperationDone() {
		$backend = $this->getHistoryBackendInstance();

		if( !$backend ) {
			return;
		}
		$backend->operationDone();
	}

	/**
	 * @param array $where
	 *
	 * @return DataModel_Query
	 */
	protected function createQuery( array $where=array() ) {
		return DataModel_Query::createQuery($this->getDataModelDefinition(), $where);
	}


	/**
	 *
	 * @param array| $query
	 * @return DataModel
	 */
	protected static function fetchOneObject( array $query ) {

		$fetch = new DataModel_Fetch_Object_Assoc( $query, static::getDataModelDefinition() );
		$fetch->getQuery()->setLimit(1);

		foreach($fetch as $object) {
			return $object;
		}

		return false;
	}

	/**
	 *
	 * @param array $query
	 * @return DataModel_Fetch_Object_Assoc
	 */
	protected static function fetchObjects( array  $query=array() ) {
		return new DataModel_Fetch_Object_Assoc( $query, static::getDataModelDefinition() );
	}

	/**
	 *
	 * @param array $query
	 * @return DataModel_Fetch_Object_IDs
	 */
	protected static function fetchObjectIDs( array $query=array() ) {
		return new DataModel_Fetch_Object_IDs( $query, static::getDataModelDefinition() );
	}


	/**
	 *
	 * @param array $load_items
	 * @param array $query
	 * @return DataModel_Fetch_Data_All
	 */
	protected static function fetchDataAll( array $load_items, array  $query=array() ) {
		return new DataModel_Fetch_Data_All( $load_items, $query, static::getDataModelDefinition() );
	}

	/**
	 *
	 * @param array $load_items
	 * @param array $query
	 * @return DataModel_Fetch_Data_Assoc
	 */
	protected static function fetchDataAssoc( array $load_items, array  $query=array() ) {
		return new DataModel_Fetch_Data_Assoc( $load_items, $query, static::getDataModelDefinition() );
	}

	/**
	 *
	 * @param array $load_items
	 * @param array $query
	 * @return DataModel_Fetch_Data_Pairs
	 */
	protected static function fetchDataPairs( array $load_items, array  $query=array() ) {
		return new DataModel_Fetch_Data_Pairs( $load_items, $query, static::getDataModelDefinition() );
	}

	/**
	 *
	 * @param array $load_items
	 * @param array $query
	 * @return mixed|null
	 */
	protected static function fetchDataRow( array $load_items, array  $query=array() ) {
		$query = DataModel_Query::createQuery(static::getDataModelDefinition(), $query);
		$query->setSelect($load_items);

		return static::getBackendInstance()->fetchRow( $query );

	}

	/**
	 *
	 * @param array $load_items
	 * @param array $query
	 *
	 * @return mixed|null
	 */
	protected static function fetchDataOne( array $load_items, array  $query=array() ) {
		$query = DataModel_Query::createQuery(static::getDataModelDefinition(), $query);
		$query->setSelect($load_items);

		return static::getBackendInstance()->fetchOne( $query );
	}

	/**
	 *
	 * @param string $form_name
	 * @param array $only_properties
	 *
	 * @throws DataModel_Exception
	 * @return Form
	 */
	protected function getForm( $form_name, array $only_properties ) {
		$definition = $this->getDataModelDefinition();

		$fields = array();

		foreach($definition->getProperties() as $property_name=>$property) {
			if( !in_array($property_name, $only_properties) ) {
				continue;
			}

			$field = $property->getFormField();
			if(!$field) {
				$class = $definition->getClassName();

				throw new DataModel_Exception(
					'The property '.$class.'::'.$property.' is required for form definition. But property definition '.get_class($property).' prohibits the use of property as form field. ',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);

			}

			$field->setDefaultValue( $this->{$property->getName()} );

			$fields[] = $field;
		}

		return new Form( $form_name, $fields );

	}

	/**
	 * @param string $form_name
	 * @param bool $skip_hidden_fields (optional, default=false)
	 *
	 * @return Form
	 */
	public function getCommonForm( $form_name='', $skip_hidden_fields=false ) {
		$definition = $this->getDataModelDefinition();


		$only_properties = array();

		foreach($definition->getProperties() as $property_name => $property) {
			$field = $property->getFormField();

			if(!$field) {
				continue;
			}

			if(
				$skip_hidden_fields &&
				$field instanceof Form_Field_Hidden
			) {
				continue;
			}


			$only_properties[] = $property_name;
		}

		if(!$form_name) {
			//$form_name = $definition->getClassName();
			$form_name = $this->getClassNameWithoutNamespace();
		}

		return $this->getForm($form_name, $only_properties);
	}

	/**
	 * @param Form $form
	 *
	 * @param array $data
	 * @param bool $force_catch
	 *
	 * @return bool;
	 */
	public function catchForm( Form $form, $data=null, $force_catch=false   ) {
		if(
			!$form->catchValues($data, $force_catch) ||
			!$form->validateValues()
		) {
			return false;
		}

		$data = $form->getValues();

		$properties = $this->getDataModelDefinition()->getProperties();

		foreach( $data as $key=>$val ) {
			$field = $form->getField($key);

			$callback = $field->getCatchDataCallback();

			if($callback) {
				$callback( $field->getValueRaw() );
				continue;
			}

			if(
				!isset($properties[$key]) ||
				$properties[$key]->getIsID()
			) {
				continue;
			}

			$setter_method_name = $this->getSetterMethodName( $key );

			if(method_exists($this, $setter_method_name)) {
				$this->{$setter_method_name}($val);
			} else {
				$this->_setPropertyValue($key, $val);
			}


		}

		return true;
	}

	/**
	 * @return string
	 */
	public function toXML() {
		return $this->_XMLSerialize();
	}

	/**
	 * @return string
	 */
	public function toJSON() {
		$data = $this->jsonSerialize();
		return json_encode($data);
	}

	/**
	 * @param string $prefix
	 *
	 * @return string
	 */
	protected function _XMLSerialize( $prefix='' ) {
		$definition = $this->getDataModelDefinition();
		$properties = $definition->getProperties();

		$model_name = $definition->getModelName();

		$result = $prefix.'<'.$model_name.'>'.JET_EOL;

		foreach($properties as $property_name=>$property) {
			if($property->getDoNotSerialize()) {
				continue;
			}
			$result .= $prefix.JET_TAB.'<!-- '.$property->getTechnicalDescription().' -->'.JET_EOL;

			$val = $this->{$property_name};

			if($property->getIsDataModel()) {
				$result .= $prefix.JET_TAB.$property_name.JET_EOL;
				if($val) {
					/**
					 * @var DataModel $val
					 */
					$result .= $val->_XMLSerialize( $prefix.JET_TAB );
				}
				$result .= $prefix.JET_TAB.'</'.$property_name.'>'.JET_EOL;

			} else {
				if(is_array($val)) {
					$result .= $prefix.JET_TAB.'<'.$property_name.'>'.JET_EOL;
					foreach($val as $k=>$v) {
						if(is_numeric($k)) {
							$k = 'item';
						}
						$result .= $prefix.JET_TAB.JET_TAB.'<'.$k.'>'.htmlspecialchars($v).'</'.$k.'>'.JET_EOL;

					}
					$result .= $prefix.JET_TAB.'</'.$property_name.'>'.JET_EOL;
				} else {
					$result .= $prefix.JET_TAB.'<'.$property_name.'>'.htmlspecialchars($val).'</'.$property_name.'>'.JET_EOL;
				}

			}
		}
		$result .= $prefix.'</'.$model_name.'>'.JET_EOL;

		return $result;
	}


	/**
	 * @return array
	 */
	public function jsonSerialize() {
		$properties = $this->getDataModelDefinition()->getProperties();

		$result = array();
		foreach($properties as $property_name=>$property) {
			if($property->getDoNotSerialize()) {
				continue;
			}

			if($property->getIsDataModel()) {
				if($this->{$property_name}) {
					/**
					 * @var DataModel $prop
					 */
					$prop = $this->{$property_name};
					$result[$property_name] = $prop->jsonSerialize();
				} else {
					$result[$property_name] = null;
				}
			} else {
				$result[$property_name] = $property->getValueForJsonSerialize( $this->{$property_name} );
			}
		}

		return $result;
	}

	/**
	 *
	 * @return array
	 */
	public function __sleep() {
		return array_keys($this->getDataModelDefinition()->getProperties());
	}

	/**
	 *
	 */
	public function __wakeup() {
		$this->___data_model_saved = true;
		$this->___data_model_ready_to_save = false;
	}

	/**
	 *
	 */
	public function __clone() {
		$this->resetID();
		$this->setIsNew();
	}


	/**
	 * @param string $class
	 *
	 * @return string[]
	 */
	public static function helper_getCreateCommand( $class ) {
		//DO NOT use factory here!
		/**
		 * @var DataModel $_this
		 */
		$_this = new $class();

		return $_this->getBackendInstance()->helper_getCreateCommand( $_this );
	}

	/**
	 *
	 * @param string $class
	 * @param bool $including_history_backend (optional, default: true)
	 * @param bool $including_cache_backend (optional, default: true)
	 * @return bool
	 */
	public static function helper_create( $class, $including_history_backend=true, $including_cache_backend=true ) {
		//DO NOT use factory here!
		/**
		 * @var DataModel $_this
		 */
		$_this = new $class();

		if( $including_history_backend ) {
			$h_backend = $_this->getHistoryBackendInstance();

			if($h_backend) {
				$h_backend->helper_create();
			}
		}

		if($including_cache_backend) {
			$c_backend = $_this->getCacheBackendInstance();

			if($c_backend) {
				$c_backend->helper_create();
			}

		}

		return $_this->getBackendInstance()->helper_create( $_this );
	}


	/**
	 * Update (actualize) DB table or tables
	 *
	 * @param string $class
	 *
	 * @return string
	 */
	public static function helper_getUpdateCommand( $class ) {
		//DO NOT use factory here!
		/**
		 * @var DataModel $_this
		 */
		$_this = new $class();

		return $_this->getBackendInstance()->helper_getUpdateCommand( $_this );
	}

	/**
	 * Update (actualize) DB table or tables
	 *
	 * @param bool $including_history_backend (optional, default: true)
	 * @param bool $including_cache_backend (optional, default: true)
	 *
	 * @param string $class
	 */
	public static function helper_update( $class, $including_history_backend=true, $including_cache_backend=true  ) {
		//DO NOT use factory here!
		/**
		 * @var DataModel $_this
		 */
		$_this = new $class();

		if( $including_history_backend ) {
			$h_backend = $_this->getHistoryBackendInstance();

			if($h_backend) {
				$h_backend->helper_create();
			}
		}

		if($including_cache_backend) {
			$c_backend = $_this->getCacheBackendInstance();

			if($c_backend) {
				$c_backend->helper_create();
			}

		}

		$_this->getBackendInstance()->helper_update( $_this );

		$cache = $_this->getCacheBackendInstance();
		if($cache) {
			$cache->truncate( $_this->getDataModelDefinition()->getModelName() );
		}
	}

	/**
	 * Drop (only rename by default) DB table or tables
	 *
	 * @param string $class
	 */
	public static function helper_drop( $class ) {
		//DO NOT use factory here!
		/**
		 * @var DataModel $_this
		 */
		$_this = new $class();

		$_this->getBackendInstance()->helper_drop( $_this );

		$cache = $_this->getCacheBackendInstance();
		if($cache) {
			$cache->truncate( $_this->getDataModelDefinition()->getModelName() );
		}

	}


	/**
	 * @param &$reflection_data
	 * @param string $key
	 * @param string $definition
	 * @param mixed $raw_value
	 * @param mixed $value
	 *
	 * @throws Object_Reflection_Exception
	 */
	public static function parseClassDocComment( &$reflection_data, $key, $definition, $raw_value, $value ) {
		DataModel_Definition_Model_Abstract::parseClassDocComment( get_called_class(), $reflection_data, $key, $definition, $raw_value, $value );
	}

	/**
	 * @param array &$reflection_data
	 * @param string $property_name
	 * @param string $key
	 * @param string $definition
	 * @param mixed $raw_value
	 * @param mixed $value
	 *
	 * @throws Object_Reflection_Exception
	 */
	public static function parsePropertyDocComment( &$reflection_data,$property_name, $key, $definition, $raw_value, $value ) {
		DataModel_Definition_Model_Abstract::parsePropertyDocComment( get_called_class(), $reflection_data,$property_name, $key, $definition, $raw_value, $value );
	}

}
