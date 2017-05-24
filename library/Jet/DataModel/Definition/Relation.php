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
abstract class DataModel_Definition_Relation extends BaseObject
{

	/**
	 * @var string
	 */
	protected $related_data_model_class_name;

	/**
	 *
	 * @var DataModel_Definition_Model
	 */
	protected $related_data_model_definition;


	/**
	 * @var DataModel_Definition_Relation_JoinByItem[]
	 */
	protected $join_by = [];

	/**
	 * @var string
	 */
	protected $join_type = DataModel_Query::JOIN_TYPE_LEFT_JOIN;

	/**
	 * @var array
	 */
	protected $required_relations = [];

	/**
	 * @return string
	 */
	public function getRelatedDataModelClassName()
	{
		return $this->related_data_model_class_name;
	}

	/**
	 * @return string
	 */
	public function getRelatedDataModelName()
	{
		return $this->getRelatedDataModelDefinition()->getModelName();
	}

	/**
	 * @return DataModel_Definition_Model
	 */
	public function getRelatedDataModelDefinition()
	{
		if( !$this->related_data_model_definition ) {

			$this->related_data_model_definition = DataModel::getDataModelDefinition(
				$this->related_data_model_class_name
			);
		}

		return $this->related_data_model_definition;
	}

	/**
	 * @return string
	 */
	public function getJoinType()
	{
		return $this->join_type;
	}

	/**
	 * @param string $join_type
	 */
	public function setJoinType( $join_type )
	{
		$this->join_type = $join_type;
	}

	/**
	 * @return DataModel_Definition_Relation_JoinByItem[]
	 */
	public function getJoinBy()
	{
		return $this->join_by;
	}

	/**
	 * @param array $items
	 */
	public function setJoinBy( array $items )
	{
		$this->join_by = [];

		foreach( $items as $item ) {
			$this->addJoinBy( $item );
		}
	}

	/**
	 * @param DataModel_Definition_Relation_JoinByItem $item
	 */
	public function addJoinBy( DataModel_Definition_Relation_JoinByItem $item )
	{
		$this->join_by[] = $item;
	}

	/**
	 * @return array
	 */
	public function getRequiredRelations()
	{
		return $this->required_relations;
	}

	/**
	 * @param array $required_relations
	 */
	public function setRequiredRelations( array $required_relations )
	{
		$this->required_relations = $required_relations;
	}

	/**
	 * @param string $related_to_class_name
	 */
	protected function setRelatedToClass( $related_to_class_name )
	{
		$this->related_data_model_class_name = $related_to_class_name;
	}

}