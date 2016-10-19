<?php 
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Form
 */
namespace Jet;

class Form_Field_RadioButton extends Form_Field_Abstract {
	const ERROR_CODE_INVALID_VALUE = 'invalid_value';

	/**
	 * @var string
	 */
	protected $_type = Form::TYPE_RADIO_BUTTON;

	/**
	 * @var array
	 */
	protected $error_messages = [
				self::ERROR_CODE_EMPTY => '',
				self::ERROR_CODE_INVALID_VALUE => ''
	];


	/**
	 * catch value from input (input = most often $_POST)
	 *
	 * @param Data_Array $data
	 */
	public function catchValue( Data_Array $data ) {
		$this->_value = null;
		$this->_has_value = true;

		if($data->exists($this->_name)) {
			$this->_value_raw = $data->getRaw($this->_name);
			$this->_value = trim( $data->getString($this->_name) );
		} else {
			$this->_value_raw = null;
			$this->_value = null;
		}
	}

	/**
	 * @return bool
	 */
	public function validateValue() {
		if($this->_value===null && !$this->is_required) {
			return true;
		}

		$options = $this->select_options;
		
		if(!isset($options[$this->_value])) {
			$this->setValueError(self::ERROR_CODE_INVALID_VALUE);
			return false;
		}
		
		$this->_setValueIsValid();
		
		return true;
	}

	protected function _getReplacement_field_option_label( Form_Parser_TagData $tag_data ) {
		$key = $tag_data->getProperty('key');

		if(!isset($this->select_options[$key])) {
			return '';
		}

		$tag_data->unsetProperty( 'key' );
		$tag_data->setProperty( 'for', $this->getID().'_'.$key );

		return '<label '.$this->_getTagPropertiesAsString( $tag_data ).'>'.$this->select_options[ $key ].'</label>';

	}

	protected function _getReplacement_field_option( Form_Parser_TagData $tag_data ) {
		$key = $tag_data->getProperty('key');

		if(!isset($this->select_options[$key])) {
			return '';
		}

		$tag_data->unsetProperty( 'key' );
		$tag_data->setProperty( 'name', $this->getName() );
		$tag_data->setProperty( 'id', $this->getID().'_'.$key );
		$tag_data->setProperty( 'type', 'radio' );
		$tag_data->setProperty( 'value', $key );

		if(!$tag_data->getPropertyIsSet('class')){
			$tag_data->setProperty('class', 'radio');
			$properties['class'] = 'radio';
		}
		if($this->getIsReadonly()) {
			$tag_data->setProperty( 'disabled', 'disabled' );
		}


		if($this->_value==$key) {
			$tag_data->setProperty('checked', 'checked');
		} else {
			$tag_data->unsetProperty('checked');
		}

		return '<input '.$this->_getTagPropertiesAsString($tag_data).'/>';
	}

	/**
	 * @return array
	 */
	public function getRequiredErrorCodes()
	{
		$codes = [];

		$codes[] = self::ERROR_CODE_INVALID_VALUE;

		if($this->is_required ) {
			$codes[] = self::ERROR_CODE_EMPTY;
		}


		return $codes;
	}

	public function __toString()
	{
		//TODO:
		return '';
	}
}