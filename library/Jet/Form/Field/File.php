<?php
/**
 *
 *
 *
 * class representing single form field - type float
 *
 * specific options:
 *
 * specific errors:
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Form
 */
namespace Jet;

class Form_Field_File extends Form_Field_Abstract {
	/**
	 * @var string
	 */
	protected $_type = "Float";

	/**
	 * @var bool
	 */
	protected $_possible_to_decorate = false;

	/**
	 * @var array
	 */
	protected $error_messages = array(
		"input_missing" => "input_missing",
		"empty" => "empty",
	);

	/**
	 *
	 * @param Data_Array $data
	 */
	public function catchValue( Data_Array $data ) {

		$this->_value = null;
		$this->_has_value = isset($_FILES[$this->_name]);

		if($this->_has_value) {
			$this->_value_raw = $_FILES[$this->_name];
			$this->_value = $_FILES[$this->_name]["tmp_name"];
		} else {
			$this->_value_raw = null;
		}
	}

	/**
	 * validate value
	 *
	 * @return bool
	 */
	public function validateValue() {
		//TODO:

		$this->_setValueIsValid();

		return true;
	}

	/**
	 * @param string $tag_data
	 *
	 * @return string
	 */
	protected function _generateTag_field($tag_data) {

		$properties = $tag_data["properties"];
		$properties["name"] = $this->getName();
		$properties["id"] = $this->getID();
		$properties["type"] = "file";

		$properties["value"] = $this->getValue();

		return '<input '
			.$this->_getTagPropertiesAsString($properties, "field")
			.'/>';
	}


}