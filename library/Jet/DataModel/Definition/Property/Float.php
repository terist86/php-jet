<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Definition
 */
namespace Jet;

class DataModel_Definition_Property_Float extends DataModel_Definition_Property_Abstract {
	/***
	 * @var string
	 */
	protected $_type = DataModel::TYPE_FLOAT;

	/**
	 * @var float
	 */
	protected $default_value = 0.0;

	/**
	 * @var string
	 */
	protected $form_field_type = Form::TYPE_FLOAT;

	/**
	 * @param array $definition_data
	 *
	 */
	public function setUp( $definition_data ) {
		if(!$definition_data) {
			return;
		}

		parent::setUp($definition_data);

		if($this->form_field_min_value!==null) {
			$this->form_field_min_value = (float)$this->form_field_min_value;
		}
		if($this->form_field_max_value!==null) {
			$this->form_field_max_value = (float)$this->form_field_max_value;
		}

	}

	/**
	 * @param float &$value
	 */
	public function checkValueType( &$value ) {
		$value = (float)$value;
	}

	/**
	 * @return string
	 */
	public function getTechnicalDescription() {
		$res = 'Type: '.$this->getType().' ';

		$res .= ', required: '.($this->form_field_is_required ? 'yes':'no');

		if($this->is_ID) {
			$res .= ', is ID';
		}

		if($this->default_value) {
			$res .= ', default value: '.$this->default_value;
		}

		if($this->form_field_min_value) {
			$res .= ', min. value: '.$this->form_field_min_value;
		}

		if($this->form_field_max_value) {
			$res .= ', max. value: '.$this->form_field_max_value;
		}

		if($this->description) {
			$res .= JET_EOL.JET_EOL.$this->description;
		}

		return $res;
	}

}