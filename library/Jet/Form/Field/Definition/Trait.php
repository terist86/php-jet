<?php
/**
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
 * @package Form
 */
namespace Jet;

trait Form_Field_Definition_Trait {
	/**
	 * @var string
	 */
	protected $form_field_creator_method_name = '';

	/**
	 *
	 * @var string
	 */
	protected $form_field_type;

	/**
	 * @var bool
	 */
	protected $form_field_is_required = false;

	/**
	 *
	 * @var string
	 */
	protected $form_field_label = '';

	/**
	 * @var null|string
	 */
	protected $form_field_validation_regexp = null;

	/**
	 * @var null|int|float
	 */
	protected $form_field_min_value = null;
	/**
	 * @var null|int|float
	 */
	protected $form_field_max_value = null;

	/**
	 *
	 * @var array
	 */
	protected $form_field_error_messages = [];

	/**
	 *
	 * @var callable
	 */
	protected $form_field_get_select_options_callback;

	/**
	 * @var string
	 */
	protected $form_catch_value_method_name;

	/**
	 *
	 * @var array
	 */
	protected $form_field_options = [];


	/**
	 * @return string
	 */
	public function getFormFieldType()
	{
		return $this->form_field_type;
	}


	/**
	 * @param string $type
	 */
	public function setFormFieldType( $type )
	{
		$this->form_field_type = $type;
	}

	/**
	 * @param string $form_field_creator_method_name
	 */
	public function setFormFieldCreatorMethodName($form_field_creator_method_name) {
		$this->form_field_creator_method_name = $form_field_creator_method_name;
	}

	/**
	 * @return string
	 */
	public function getFormFieldCreatorMethodName() {
		return $this->form_field_creator_method_name;
	}

	/**
	 * @param callable $form_field_get_select_options_callback
	 */
	public function setFormFieldGetSelectOptionsCallback($form_field_get_select_options_callback) {
		$this->form_field_get_select_options_callback = $form_field_get_select_options_callback;
	}

	/**
	 * @return callable
	 */
	public function getFormFieldGetSelectOptionsCallback() {
		return $this->form_field_get_select_options_callback;
	}

	/**
	 * @param string $form_catch_value_method_name
	 */
	public function setFormCatchValueMethodName($form_catch_value_method_name)
	{
		$this->form_catch_value_method_name = $form_catch_value_method_name;
	}

	/**
	 * @return string
	 */
	public function getFormCatchValueMethodName()
	{
		return $this->form_catch_value_method_name;
	}


	/**
	 * @return bool
	 */
	public function getFormFieldIsRequired() {
		return $this->form_field_is_required;
	}


	/**
	 * @return string|null
	 */
	public function getFormFieldValidationRegexp() {
		return $this->form_field_validation_regexp;
	}


	/**
	 * @return int|float|null
	 */
	public function getFormFieldMinValue() {
		return $this->form_field_min_value;
	}

	/**
	 * @return int|float|null
	 */
	public function getFormFieldMaxValue() {
		return $this->form_field_max_value;
	}

	/**
	 * @param array $options
	 */
	public function setFormFieldOptions( array $options ) {
		$this->form_field_options = $options;
	}

	/**
	 * @return array
	 */
	public function getFormFieldOptions() {

		if(
			$this->form_field_validation_regexp &&
			!isset($this->form_field_options['validation_regexp'])
		) {
			$this->form_field_options['validation_regexp'] = $this->form_field_validation_regexp;
		}

		if(
			$this->form_field_min_value!==null &&
			!array_key_exists('min_value', $this->form_field_options)
		) {
			$this->form_field_options['min_value'] = $this->form_field_min_value;
		}

		if(
			$this->form_field_max_value!==null &&
			!array_key_exists('max_value', $this->form_field_options)
		) {
			$this->form_field_options['max_value'] = $this->form_field_max_value;
		}


		return $this->form_field_options;
	}


	/**
	 * @param string $label
	 */
	public function setFormFieldLabel( $label ) {
		$this->form_field_label = $label;
	}

	/**
	 * @return string
	 */
	public function getFormFieldLabel() {
		return $this->form_field_label;
	}


	/**
	 * @param array $messages
	 *
	 */
	public function setFormFieldErrorMessages( array $messages ){
		$this->form_field_error_messages = $messages;
	}

	/**
	 * @return array
	 */
	public function getFormFieldErrorMessages() {
		return $this->form_field_error_messages;
	}

	/**
	 * @return array|null
	 *
	 * @throws DataModel_Exception
	 */
	public function getFormFieldSelectOptions() {
		/**
		 * @var Form_Field_Definition_Interface|Form_Field_Definition_Trait $this
		 */

		$select_options = null;

		if($this->form_field_get_select_options_callback) {
			$callback = $this->form_field_get_select_options_callback;

			if(!is_callable($callback)) {
				throw new DataModel_Exception($this->getFormFieldContextClassName().'::'.$this->getFormFieldName().'::form_field_get_select_options_callback is not callable');
			}

			$select_options = $callback();
		}

		return $select_options;
	}

	/**
	 *
	 * @param mixed $property_value
	 *
	 * @throws DataModel_Exception
	 * @return Form_Field_Abstract|Form_Field_Abstract[]
	 */
	public function createFormField( $property_value ) {
		/**
		 * @var Form_Field_Definition_Interface|Form_Field_Definition_Trait $this
		 */

		$field = Form_Factory::getFieldInstance(
			$this->getFormFieldType(),
			$this->getFormFieldName(),
			$this->getFormFieldLabel(),
			$property_value,
			$this->getFormFieldIsRequired()
		);

		$field->setErrorMessages( $this->getFormFieldErrorMessages() );
		$field->setOptions( $this->getFormFieldOptions() );

		$select_options = $this->getFormFieldSelectOptions();
		if($select_options) {
			$field->setSelectOptions( $select_options );
		}

		return $field;

	}


	/**
	 * @param $object_instance
	 * @param mixed &$property
	 * @param mixed $value
	 */
	public function catchFormField( $object_instance, &$property, $value ) {
		/**
         * @var Object $object_instance
		 * @var Form_Field_Definition_Interface|Form_Field_Definition_Trait $this
		 */

		if( ($method_name = $this->getFormCatchValueMethodName()) ) {
			$object_instance->{$method_name}($value);
			return;
		}

		$setter_method_name = $object_instance->getSetterMethodName( $this->getFormFieldContextPropertyName() );

		if( method_exists($object_instance, $setter_method_name) ) {
			$object_instance->{$setter_method_name}($value);
			return;
		}

		$property = $value;

	}

}
