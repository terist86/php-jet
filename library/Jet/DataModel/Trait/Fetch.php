<?php
/**
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
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

trait DataModel_Trait_Fetch {


    /**
     * @param array $where
     *
     * @return DataModel_Query
     */
    public function createQuery( array $where= []) {
        /**
         * @var DataModel $this
         */
        $query = new DataModel_Query($this->getDataModelDefinition() );
        $query->setMainDataModel( $this );
        $query->setWhere( $where );
        return $query;
    }

    /**
     *
     * @param array| $where
     * @return DataModel
     */
    public function fetchOneObject( array $where ) {

        $query = $this->createQuery( $where );
        $query->setLimit(1);

        $fetch = new DataModel_Fetch_Object_Assoc( $query );

        foreach($fetch as $object) {
            return $object;
        }

        return false;
    }

    /**
     *
     * @param array $where
     * @return DataModel_Fetch_Object_Assoc
     */
    public function fetchObjects( array  $where= []) {
        return new DataModel_Fetch_Object_Assoc( $this->createQuery($where) );
    }

    /**
     *
     * @param array $where
     * @return DataModel_Fetch_Object_IDs
     */
    public function fetchObjectIDs( array $where= []) {
        return new DataModel_Fetch_Object_IDs(  $this->createQuery($where)  );
    }


    /**
     *
     * @param array $load_items
     * @param array $where
     * @return DataModel_Fetch_Data_All
     */
    public function fetchDataAll( array $load_items, array  $where= []) {
        return new DataModel_Fetch_Data_All( $load_items, $this->createQuery($where) );
    }

    /**
     *
     * @param array $load_items
     * @param array $where
     * @return DataModel_Fetch_Data_Assoc
     */
    public function fetchDataAssoc( array $load_items, array  $where= []) {
        return new DataModel_Fetch_Data_Assoc( $load_items, $this->createQuery($where) );
    }

    /**
     *
     * @param array $load_items
     * @param array $where
     * @return DataModel_Fetch_Data_Pairs
     */
    public function fetchDataPairs( array $load_items, array  $where= []) {
        return new DataModel_Fetch_Data_Pairs( $load_items, $this->createQuery($where) );
    }

    /**
     *
     * @param array $load_items
     * @param array $where
     * @return mixed|null
     */
    public function fetchDataRow( array $load_items, array  $where= []) {
        $query = $this->createQuery( $where );
        $query->setSelect($load_items);

        /**
         * @var DataModel $this
         */
        return $this->getBackendInstance()->fetchRow( $query );

    }

    /**
     *
     * @param array $load_item
     * @param array $where
     *
     * @return mixed|null
     */
    public function fetchDataOne( $load_item, array  $where= []) {

        $query = $this->createQuery( $where );
        $query->setSelect( [$load_item] );

        /**
         * @var DataModel $this
         */
        return $this->getBackendInstance()->fetchOne( $query );
    }

    /**
     *
     * @param $load_item
     * @param array $where
     *
     * @return DataModel_Fetch_Data_Col
     */
    public function fetchDataCol( $load_item, array  $where= []) {
        $query = $this->createQuery( $where );

        return new DataModel_Fetch_Data_Col( $load_item, $query );
    }

}