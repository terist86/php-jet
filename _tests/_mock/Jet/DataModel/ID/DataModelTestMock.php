<?php
/**
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package DataModel
 */
namespace Jet;

/**
 * Class DataModel_ID_DataModelTestMock
 *
 * @JetDataModel:name = 'data_model_test_mock'
 * @JetDataModel:database_table_name = 'data_model_test_mock'
 * @JetDataModel:ID_class_name = 'Jet\DataModel_ID_UniqueString'
 */
class DataModel_ID_DataModelTestMock extends DataModel {

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID
	 * @JetDataModel:is_ID = true
	 *
	 * @var string
	 */
	protected $ID = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:is_ID = true
	 * @JetDataModel:max_len = 50
	 *
	 * @var string
	 */
	protected $ID_property_1 = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_LOCALE
	 * @JetDataModel:is_ID = true
	 *
	 * @var Locale
	 */
	protected $ID_property_2;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_INT
	 * @JetDataModel:is_ID = true
	 *
	 * @var int
	 */
	protected $ID_property_3 = 0;


	/**
	 */
	/** @noinspection PhpMissingParentConstructorInspection */
	public function __construct() {
	}
}