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
interface DataModel_Related_Item_Interface extends DataModel_Related_Interface
{

	/**
	 *
	 * @param DataModel_Id                  $main_id
	 * @param DataModel_PropertyFilter|null $load_filter
	 *
	 * @return array
	 */
	public static function loadRelatedData( DataModel_Id $main_id, DataModel_PropertyFilter $load_filter = null );

	/**
	 *
	 * @param array                         &$loaded_related_data
	 * @param DataModel_Id|null             $parent_id
	 * @param DataModel_PropertyFilter|null $load_filter
	 *
	 * @return mixed
	 */
	public static function loadRelatedInstances( array &$loaded_related_data, DataModel_Id $parent_id = null, DataModel_PropertyFilter $load_filter = null );

	/**
	 * @return DataModel_Related_Interface
	 */
	public function createNewRelatedDataModelInstance();


}