<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/** @noinspection PhpIncludeInspection */
require_once '_mock/Jet/DataModel/Definition/DataModelTestMock.php';

/**
 *
 */
class DataModel_Definition_Property_IdTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var DataModel_Definition_DataModelTestMock
	 */
	protected $data_model;

	/**
	 * @var DataModel_Definition_Property_Id
	 */
	protected $object;

	protected $property_class_name = 'DataModel_Definition_Property_Id';

	protected $property_name = 'IdTest';

	protected $property_options = [
		'description' => 'Description',
		'default_value' => 'default value',
		'do_not_export' => true,


	];

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$class_name = __NAMESPACE__.'\\'.$this->property_class_name;

		$this->data_model = new DataModel_Definition_DataModelTestMock();

		$this->object = new $class_name( get_class($this->data_model), $this->property_name, $this->property_options );
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}

	/**
	 * @covers \Jet\DataModel_Definition_Property_Id::checkValueType
	 */
	public function testCheckValueType() {
		$value = 'do not change';
		$this->object->checkValueType($value);
		$this->assertEquals($value, $value);
	}
}