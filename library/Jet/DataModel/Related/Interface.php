<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2015 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Related
 */
namespace Jet;

interface DataModel_Related_Interface extends Object_Serializable_REST {

    /**
     * @return DataModel_Related_Interface
     */
    public function createNewRelatedDataModelInstance();


    /**
     * @param DataModel $main_model_instance
     * @param DataModel_Related_Abstract $parent_model_instance (optional)
     *
     */
    public function setupParentObjects( DataModel $main_model_instance, DataModel_Related_Abstract $parent_model_instance=null );

    /**
     * @return array
     */
    public function loadRelatedData();

    /**
     * @param array &$loaded_related_data
     * @return mixed
     */
    public function createRelatedInstancesFromLoadedRelatedData( array &$loaded_related_data );


    /**
     * @return DataModel_Validation_Error[]
     */
    public function getValidationErrors();

    /**
     *
     */
    public function save();

    /**
     *
     */
    public function delete();

    /**
     * @return array
     */
    public function getCommonFormPropertiesList();

    /**
     *
     * @param DataModel_Definition_Property_Abstract $parent_property_definition
     * @param array $properties_list
     *
     * @return Form_Field_Abstract[]
     */
    public function getRelatedFormFields( DataModel_Definition_Property_Abstract $parent_property_definition, array $properties_list );

    /**
     * @param array $values
     *
     * @return bool
     */
    public function catchRelatedForm( array $values );


    /**
     *
     */
    public function __wakeup_relatedItems();



}