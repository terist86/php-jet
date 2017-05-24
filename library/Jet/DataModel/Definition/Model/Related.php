<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class DataModel_Definition_Model_Related extends DataModel_Definition_Model
{


	/**
	 * @var string
	 */
	protected $main_model_class_name = '';

	/**
	 * @var DataModel_Definition_Relation_JoinByItem[]
	 */
	protected $main_model_relation_join_items = [];

	/**
	 *
	 * @var DataModel_Definition_Property[]
	 */
	protected $main_model_relation_id_properties = [];

	/**
	 *
	 * @var bool
	 */
	protected $is_sub_related_model = false;

	/**
	 * @var string
	 */
	protected $parent_model_class_name = '';

	/**
	 * @var DataModel_Definition_Relation_JoinByItem[]
	 */
	protected $parent_model_relation_join_items = [];

	/**
	 * @var array
	 */
	protected $default_order_by = [];

	/**
	 *
	 * @var DataModel_Definition_Property[]
	 */
	protected $parent_model_relation_id_properties = [];

	/**
	 * @var array
	 */
	protected $__main_id_glue_defined = [];

	/**
	 * @var array
	 */
	protected $__parent_id_glue_defined = [];


	/** @noinspection PhpMissingParentConstructorInspection
	 *
	 * @param string $data_model_class_name (optional)
	 *
	 * @throws DataModel_Exception
	 */
	public function __construct( $data_model_class_name = '' )
	{
		if( $data_model_class_name ) {
			$this->_mainInit( $data_model_class_name );

			$this->_initParents();
			$this->_initProperties();
			$this->_initKeys();

			$this->default_order_by = Reflection::get( $this->class_name, 'default_order_by', [] );

			if( !$this->id_properties ) {
				throw new DataModel_Exception(
					'There are not any ID properties in DataModel \''.$this->getClassName().'\' definition',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);
			}
		}
	}

	/**
	 * @throws DataModel_Exception
	 */
	protected function _initParents()
	{

		$parent_model_class_name = Reflection::get(
			$this->class_name, 'data_model_parent_model_class_name'
		);

		if( !$parent_model_class_name ) {
			throw new DataModel_Exception(
				$this->class_name.' @JetDataModel:parent_model_class_name is not defined!',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		$this->parent_model_class_name = $parent_model_class_name;

		$main_model_class_name = $parent_model_class_name;

		// Traversing and seeking for main model
		while( ( $_parent = Reflection::get(
			$main_model_class_name, 'data_model_parent_model_class_name'
		) ) ) {

			$main_model_class_name = $_parent;

			$this->is_sub_related_model = true;
		}

		if( !is_subclass_of( $main_model_class_name, __NAMESPACE__.'\DataModel' ) ) {
			throw new DataModel_Exception(
				'Main parent class '.$main_model_class_name.' is not subclass of '.__NAMESPACE__.'\DataModel ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		$this->main_model_class_name = $main_model_class_name;

	}


	/**
	 *
	 */
	protected function _initProperties()
	{

		parent::_initProperties();

		$related_definition_data = $this->_getPropertiesDefinitionData( $this->main_model_class_name );
		foreach( $related_definition_data as $property_name => $pd ) {
			if( empty( $pd['is_id'] ) ) {
				continue;
			}

			if( !in_array( $property_name, $this->__main_id_glue_defined ) ) {
				throw new DataModel_Exception(
					'Class: \''.$this->class_name.'\'  Main model relation property is missing! Please declare property with this annotation: @JetDataModel:related_to = \'main.'.$property_name.'\' ',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);
			}
		}

		if( $this->is_sub_related_model ) {
			$related_definition_data = $this->_getPropertiesDefinitionData( $this->parent_model_class_name );

			foreach( $related_definition_data as $property_name => $pd ) {

				if(
					empty( $pd['is_id'] ) ||
					!empty( $pd['related_to'] )
				) {
					continue;
				}

				if( !in_array( $property_name, $this->__parent_id_glue_defined ) ) {
					throw new DataModel_Exception(
						'Class: \''.$this->class_name.'\'  parent model relation property is missing! Please declare property with this annotation: @JetDataModel:related_to = \'parent.'.$property_name.'\' ',
						DataModel_Exception::CODE_DEFINITION_NONSENSE
					);
				}
			}
		}
	}

	/**
	 *
	 * @return DataModel_Definition_Property[]
	 */
	public function getMainModelRelationIdProperties()
	{
		return $this->main_model_relation_id_properties;
	}

	/**
	 *
	 * @return DataModel_Definition_Property[]
	 */
	public function getParentModelRelationIdProperties()
	{
		return $this->parent_model_relation_id_properties;
	}

	/**
	 * @return DataModel_Definition_Relation_JoinByItem[]
	 */
	public function getParentModelRelationJoinItems()
	{
		return $this->parent_model_relation_join_items;
	}

	/**
	 * @return string
	 */
	public function getMainModelClassName()
	{
		return $this->main_model_class_name;
	}

	/**
	 * @return string
	 */
	public function getParentModelClassName()
	{
		return $this->parent_model_class_name;
	}

	/**
	 *
	 * @return DataModel_Definition_Model_Main
	 */
	public function getMainModelDefinition()
	{
		return DataModel_Definition::get( $this->main_model_class_name );
	}
	/**
	 *
	 * @return DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN
	 */
	public function getParentModelDefinition()
	{
		return DataModel_Definition::get( $this->parent_model_class_name );
	}


	/**
	 *
	 * @param DataModel_Definition_Relations $internal_relations
	 *
	 */
	public function getInternalRelations( DataModel_Definition_Relations $internal_relations )
	{

		$internal_relations[$this->getModelName()] = new DataModel_Definition_Relation_Internal(
			$this, $this->getMainModelRelationJoinItems()
		);

		foreach( $this->properties as $related_property_definition ) {
			$related_property_definition->getInternalRelations( $internal_relations );
		}

	}

	/**
	 * @return DataModel_Definition_Relation_JoinByItem[]
	 */
	public function getMainModelRelationJoinItems()
	{
		return $this->main_model_relation_join_items;
	}

	/**
	 * @return array
	 */
	public function getDefaultOrderBy()
	{
		return $this->default_order_by;
	}

	/**
	 * @param array $default_order_by
	 */
	public function setDefaultOrderBy( $default_order_by )
	{
		$this->default_order_by = $default_order_by;
	}

	/**
	 * @param string $this_id_property_name
	 * @param string $related_to
	 * @param array  $property_definition_data
	 *
	 * @throws DataModel_Exception
	 * @return DataModel_Definition_Property
	 *
	 */
	protected function _initGlueProperty( $this_id_property_name, $related_to, $property_definition_data )
	{

		$related_to = explode( '.', $related_to );

		if( count( $related_to )!=2 ) {
			throw new DataModel_Exception(
				'Invalid @JetDataModel:related_to definition format. Examples: @JetDataModel:related_to=\'parent.id\', @JetDataModel:related_to=\'main.id\'  ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		list( $what, $related_to_property_name ) = $related_to;

		if(
			(
				$what!='parent' &&
				$what!='main'
			) ||
			!$related_to_property_name
		) {
			throw new DataModel_Exception(
				'Invalid @JetDataModel:related_to definition format. Examples: @JetDataModel:related_to=\'parent.id\', @JetDataModel:related_to=\'main.id\'  ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}


		if( !$this->is_sub_related_model && $what=='parent' ) {
			throw new DataModel_Exception(
				'Invalid @JetDataModel:related_to = \'parent.'.$related_to_property_name.'\' definition. Use: @JetDataModel:related_to = \'main.'.$related_to_property_name.'\'  ',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		$related_to_class_name = '';


		if( $what=='parent' ) {
			$related_to_class_name = $this->parent_model_class_name;
			$related_definition_data = $this->_getPropertiesDefinitionData( $related_to_class_name );
			$target_properties_array = &$this->parent_model_relation_id_properties;
			$target_join_array = &$this->parent_model_relation_join_items;
			$target_glue_defined = &$this->__parent_id_glue_defined;
		}

		if( $what=='main' ) {
			$related_to_class_name = $this->main_model_class_name;
			$related_definition_data = $this->_getPropertiesDefinitionData( $related_to_class_name );
			$target_properties_array = &$this->main_model_relation_id_properties;
			$target_join_array = &$this->main_model_relation_join_items;
			$target_glue_defined = &$this->__main_id_glue_defined;
		}

		if( !isset( $related_definition_data[$related_to_property_name] ) ) {
			throw new DataModel_Exception(
				'Unknown relation property \''.$related_to_class_name.'.'.$related_to_property_name.'\'',
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);

		}

		$parent_id_property_data = $related_definition_data[$related_to_property_name];

		$parent_id_property_data['is_key'] = true;

		$parent_id_property_data['is_id'] = isset( $property_definition_data['is_id'] ) ?
			$property_definition_data['is_id'] : true;
		if( isset( $property_definition_data['form_field_type'] ) ) {
			$parent_id_property_data['form_field_type'] = $property_definition_data['form_field_type'];
		}

		$this_id_property = DataModel_Factory::getPropertyDefinitionInstance(
			$this->class_name, $this_id_property_name, $parent_id_property_data
		);

		$this_id_property->setUpRelation( $related_to_class_name, $related_to_property_name );

		$this->properties[$this_id_property_name] = $this_id_property;
		$target_properties_array[$this_id_property_name] = $this_id_property;

		$target_join_array[] = new DataModel_Definition_Relation_JoinByItem(
			$this, $this_id_property, $related_to_class_name, $related_to_property_name
		);

		$target_glue_defined[] = $related_to_property_name;

		return $this_id_property;

	}
}