<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;


/**
 *
 */
interface DataModel_Related_Iterator_Interface extends BaseObject_Interface_ArrayEmulator
{

	/**
	 * @param DataModel_Id $parent_id
	 */
	public function actualizeParentId( DataModel_Id $parent_id );

	/**
	 * @param DataModel_Id $main_id
	 */
	public function actualizeMainId( DataModel_Id $main_id );


	/**
	 *
	 */
	public function save();

	/**
	 *
	 */
	public function delete();

	/**
	 *
	 */
	public function removeAllItems();

	/**
	 * @return DataModel_Related_Interface[]
	 */
	public function getItems();


	/**
	 *
	 * @param DataModel_Definition_Property $parent_property_definition
	 * @param DataModel_PropertyFilter|null $property_filter
	 *
	 * @return Form_Field[]
	 *
	 */
	public function getRelatedFormFields( DataModel_Definition_Property $parent_property_definition, DataModel_PropertyFilter $property_filter = null );

}