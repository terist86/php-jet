<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2015 Miroslav Marek <mirek.marek.2m@gmail.com>
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

class DataModel_Related_MtoN_Iterator implements \ArrayAccess, \Iterator, \Countable, DataModel_Related_Interface   {

    /**
     * @var string
     */
    protected $item_class_name = '';


    /**
     * @var DataModel_Related_MtoN
     */
    protected $__empty_item_instance;

    /**
     * @var DataModel
     */
    protected $__main_model_instance;

    /**
     * @var DataModel_Related_Abstract
     */
    protected $__parent_model_instance;


    /**
     * @var string|null
     */
    protected $__data_model_current_M_model_class_name = null;
    /**
     * @var string|null
     */
    protected $__data_model_current_N_model_class_name = null;

    /**
     * @var string|null
     */
    protected $__data_model_current_M_model_name = null;
    /**
     * @var string|null
     */
    protected $__data_model_current_N_model_name = null;


    /**
     * @var DataModel
     */
    protected $M_instance;

    /**
     * @var DataModel_ID_Abstract
     */
    protected $M_ID = null;


    /**
     * @var DataModel_Related_MtoN[]
     */
    protected $items = null;

    /**
     * @var DataModel_Related_MtoN[]
     */
    protected $deleted_items = array();



    /**
     * @param $item_class_name
     */
    public function __construct( $item_class_name ) {

        $this->item_class_name = $item_class_name;
    }

    /**
     * @return DataModel_Related_MtoN
     */
    protected function _getEmptyItemInstance() {
        if(!$this->__empty_item_instance) {
            $this->__empty_item_instance = new $this->item_class_name();

            $this->__empty_item_instance->setupParentObjects( $this->__main_model_instance, $this->__parent_model_instance );
        }

        return $this->__empty_item_instance;

    }

    /**
     * @param array $where
     */
    public function setLoadRealtedDataWhereQueryPart( array $where)
    {
        $this->_getEmptyItemInstance()->setLoadRealtedDataWhereQueryPart($where);
    }

    /**
     * @param array $order_by
     */
    public function setLoadRealtedDataOrderBy( array $order_by)
    {
        $this->_getEmptyItemInstance()->setLoadRealtedDataOrderBy( $order_by );
    }

    /**
     * @param $data_model_class_name
     *
     * @return DataModel_Definition_Model_Related_Abstract
     */
    public static function _getDataModelDefinitionInstance( $data_model_class_name ) {
        return new DataModel_Definition_Model_Related_MtoN( $data_model_class_name );
    }


    /**
     * @param DataModel $main_model_instance
     * @param DataModel_Related_Abstract $parent_model_instance (optional)
     *
     * @throws DataModel_Exception
     */
    public function setupParentObjects(DataModel $main_model_instance, DataModel_Related_Abstract $parent_model_instance = null)
    {
        $this->__main_model_instance = $main_model_instance;
        $this->__parent_model_instance = $parent_model_instance;

        if( $parent_model_instance ) {
            $M_instance = $parent_model_instance;
        } else {
            $M_instance = $main_model_instance;
        }


        /**
         * @var DataModel $M_instance
         */
        $M_model_name = $M_instance->getDataModelDefinition()->getModelName();

        /**
         * @var DataModel_Definition_Model_Related_MtoN $data_model_definition
         */
        $data_model_definition = DataModel::getDataModelDefinition($this->item_class_name);

        if(!$data_model_definition->getRelatedModelDefinition($M_model_name)  ) {
            throw new DataModel_Exception(
                'Class \''.get_class($M_instance).'\' (model name: \''.$M_model_name.'\') is not related to \''.get_class($this).'\'  (class: \''.get_called_class().'\') ',
                DataModel_Exception::CODE_DEFINITION_NONSENSE
            );
        }

        $N_model_name = $data_model_definition->getNModelName($M_model_name);


        $this->__data_model_current_M_model_name = $M_model_name;
        $this->__data_model_current_N_model_name = $N_model_name;

        $this->__data_model_current_M_model_class_name = $data_model_definition->getRelatedModelDefinition($M_model_name)->getClassName();
        $this->__data_model_current_N_model_class_name = $data_model_definition->getRelatedModelDefinition($N_model_name)->getClassName();


        $this->M_instance = $M_instance;
        $this->M_ID = $M_instance->getID();

        $this->_getEmptyItemInstance()->setupParentObjects($main_model_instance, $parent_model_instance);

        if($this->items) {
            foreach( $this->items as $item ) {
                $item->setupParentObjects( $main_model_instance, $parent_model_instance );
            }

        }

    }

    /**
     * Save data.
     * CAUTION: Call validateProperties first!
     *
     *
     * @throws Exception
     * @throws DataModel_Exception
     */
    public function save() {

        foreach($this->deleted_items as $item) {
            /**
             * @var DataModel_Related_MtoN $item
             */
            if($item->getIsSaved()) {
                $item->delete();
            }
        }

        if( !$this->items ) {
            return;
        }

        foreach($this->items as $item) {
            $item->setupParentObjects($this->__main_model_instance, $this->__parent_model_instance);
            $item->save();
        }

    }


    /**
     *
     * @throws DataModel_Exception
     */
    public function delete() {
        foreach($this->deleted_items as $item) {
            $item->delete();
        }

        if( !$this->items ) {
            return;
        }

        foreach($this->items as $d) {
            if($d->getIsSaved()) {
                $d->delete();
            }
        }
    }


    /**
     *
     */
    public function removeAllItems() {
        if($this->items) {
            $this->deleted_items = $this->items;
        }
        $this->items = array();
    }

    /**
     * @param DataModel[] $N_instances
     *
     * @throws DataModel_Exception
     */
    public function addItems( $N_instances ) {
        foreach( $N_instances as $N_instance ) {
            $this->offsetSet(null, $N_instance );
        }
    }

    /**
     * @param DataModel[] $N_instances
     *$this->_items
     * @throws DataModel_Exception
     */
    public function setItems( $N_instances ) {

        $add_items = array();

        foreach( $this->items as $i=>$item ) {
            $exists = false;
            foreach( $N_instances as $N_instance ) {
                if($item->getNID()->toString()==$N_instance->getID()->toString()) {
                    $exists = true;
                    break;
                }
            }

            if(!$exists) {
                $this->offsetUnset($i);
            }

        }

        foreach( $N_instances as $N_instance ) {
            $exists = false;
            foreach( $this->items as $item ) {
                if($item->getNID()->toString()==$N_instance->getID()->toString()) {
                    $exists = true;
                    break;
                }
            }

            if(!$exists) {
                $add_items[] = $N_instance;
            }
        }


        if($add_items) {
            $this->addItems( $add_items );
        }

    }

    /**
     * @return DataModel_ID_Abstract[]
     */
    public function getIDs() {
        $IDs = array();

        foreach( $this->items as $item ) {
            $IDs[] = $item->getNID();
        }

        return $IDs;
    }


    /**
     *
     * @return array
     */
    public function __sleep() {
        return array();
    }

    public function __wakeup() {
    }

    /**
     * @return DataModel_Related_Interface
     */
    public function createNewRelatedDataModelInstance()
    {
        return $this;
    }

    /**
     * @return array
     */
    public function loadRelatedData()
    {
        return $this->_getEmptyItemInstance()->loadRelatedData();
    }

    /**
     * @param array &$loaded_related_data
     * @return mixed
     */
    public function createRelatedInstancesFromLoadedRelatedData(array &$loaded_related_data)
    {

        $this->deleted_items = array();

        $this->items = $this->_getEmptyItemInstance()->createRelatedInstancesFromLoadedRelatedData($loaded_related_data);

        return $this;
    }


    /**
     * @return array
     */
    public function getCommonFormPropertiesList() {
        return array();
    }


    /**
     *
     * @param DataModel_Definition_Property_Abstract $parent_property_definition
     * @param array $properties_list
     *
     * @return Form_Field_Abstract[]
     */
    public function getRelatedFormFields( DataModel_Definition_Property_Abstract $parent_property_definition, array $properties_list ) {
        return array();
    }

    /**
     * @param array $values
     *
     * @return bool
     */
    public function catchRelatedForm(array $values)
    {
        return true;
    }


    /**
     * @return DataModel_Validation_Error[]
     */
    public function getValidationErrors()
    {
        $result = array();

        if($this->items) {
            foreach( $this->items as $item) {
                foreach( $item->getValidationErrors() as $error ) {
                    $result[] = $error;
                }
            }
        }

        return $result;
    }

    /**
     *
     */
    public function __wakeup_relatedItems() {
        if($this->items) {
            foreach( $this->items as $item ) {
                $item->__wakeup_relatedItems();
            }
        }
    }

    /**
     * Validates data and returns true if everything is OK and ready to save
     *
     * @throws DataModel_Exception
     * @return bool
     */
    public function validateProperties() {
        if( !$this->items ) {
            return true;
        }

        foreach($this->items as $d) {
            if( !$d->validateProperties() ) {
                return false;
            }
        }

        return true;
    }



    /**
     * @return array
     */
    public function jsonSerialize() {

        $res = array();

        if(!$this->items) {
            return $res;
        }

        foreach($this->items as $k=>$d) {
            $res[$k] = $d->jsonSerialize();
        }

        return $res;

    }

    /**
     * @return string
     */
    public function toXML() {
        $res = array();
        if(is_array($this->items)) {
            foreach($this->items as $d) {
                /**
                 * @var DataModel_Related_MtoN $d
                 */
                $res[] = $d->toXML();
            }
        }

        return implode(JET_EOL,$res);
    }

    /**
     * @return string
     */
    public function toJSON() {
        $data = $this->jsonSerialize();
        return json_encode($data);
    }

//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------

    /**
     * @see \Countable
     *
     * @return int
     */
    public function count() {
        return count($this->items);
    }

    /**
     * @see \ArrayAccess
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists( $offset  ) {
        return isset($this->items[$offset]);
    }
    /**
     * @see \ArrayAccess
     * @param mixed $offset
     *
     * @return DataModel|DataModel_Related_MtoN
     */
    public function offsetGet( $offset ) {
        return $this->_getCurrentItem($this->items[$offset]);
    }

    /**
     *
     * @see ArrayAccess
     *
     * @param int $offset
     * @param DataModel $value
     *
     * @throws DataModel_Exception
     */
    public function offsetSet( $offset , $value ) {

        $valid_class_name = Factory::getClassName( $this->__data_model_current_N_model_class_name );

        if(!is_object($value)) {
            throw new DataModel_Exception(
                'Value instance must be instance of \''.$valid_class_name.'\'.'
            );

        }

        if(! ($value instanceof $valid_class_name) ) {
            throw new DataModel_Exception(
                'Value instance must be instance of \''.$valid_class_name.'\'. \''.get_class($value).'\' given '
            );
        }

        /**
         * @var DataModel $value
         */
        if(!$value->getIsSaved()) {
            throw new DataModel_Exception(
                'Object instance must be saved '
            );
        }

        /**
         * @var DataModel_Related_MtoN $item
         */
        $item = new $this->item_class_name();
        $item->setupParentObjects( $this->__main_model_instance, $this->__parent_model_instance );
        $item->setIsNew();

        $item->_setNDataModelInstance( $value );


        if(is_null($offset)) {
            /**
             * @var DataModel_Related_1toN $value
             */
            $offset = $item->getArrayKeyValue();
            if(is_object($offset)) {
                $offset = (string)$offset;
            }
        }

        if(!$offset) {
            $this->items[] = $item;
        } else {
            $this->items[$offset] = $item;
        }

    }

    /**
     * @see \ArrayAccess
     * @param mixed $offset
     */
    public function offsetUnset( $offset )	{
        $this->deleted_items[] = $this->items[$offset];

        unset( $this->items[$offset] );
    }

    /**
     * @see \Iterator
     *
     * @return DataModel|DataModel_Related_MtoN
     */
    public function current() {
        if( $this->items===null ) {
            return null;
        }
        $current = current($this->items);

        return $this->_getCurrentItem($current);
    }
    /**
     * @see \Iterator
     *
     * @return string
     */
    public function key() {
        if( $this->items===null ) {
            return null;
        }
        return key($this->items);
    }
    /**
     * @see \Iterator
     */
    public function next() {
        if( $this->items===null ) {
            return null;
        }
        return next($this->items);
    }
    /**
     * @see \Iterator
     */
    public function rewind() {
        if( $this->items!==null ) {
            reset($this->items);
        }
    }
    /**
     * @see \Iterator
     * @return bool
     */
    public function valid()	{
        if( $this->items===null ) {
            return false;
        }
        return key($this->items)!==null;
    }

    /**
     * @param DataModel_Related_MtoN $item
     *
     * @return DataModel_Related_MtoN
     */
    protected function _getCurrentItem( DataModel_Related_MtoN $item ) {
        return $item->getNinstance();
    }

    /**
     * @param mixed $key
     *
     * @return DataModel_Related_MtoN
     */
    public function getGlueItem( $key ) {
        return $this->items[$key];
    }

}