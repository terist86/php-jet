<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Config
 * @subpackage Config_Definition
 */
namespace Jet;

class Config_Definition_Property_Bool extends Config_Definition_Property_Abstract {
	/**
	 * @var string
	 */
	protected $_type = Config::TYPE_BOOL;

	/**
	 * @var bool
	 */
	protected $default_value = false;

	/**
	 * @var string
	 */
	protected $form_field_type = Form::TYPE_CHECKBOX;

	/**
	 * @param mixed &$value
	 */
	public function checkValueType( &$value ) {
		$value = (bool)$value;
	}

	/**
	 *
	 * @throws Config_Exception
	 *
	 * @return Form_Field_Abstract
	 */
	public function getFormField() {
		$field = parent::getFormField();
		$field->setIsRequired(false);

		return $field;
	}

	/**
	 * Property required test
	 *
	 * @param mixed &$value
	 *
	 * @throws Config_Exception
	 * @return bool
	 */
	public function _validateProperties_test_required( &$value ) {
		return true;
	}


}