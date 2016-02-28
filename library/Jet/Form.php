<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Form
 */
namespace Jet;

class Form extends Object implements Mvc_View_Postprocessor_Interface{

	const TYPE_HIDDEN = 'Hidden';

	const TYPE_INPUT = 'Input';
	const TYPE_INT = 'Int';
	const TYPE_FLOAT = 'Float';
	const TYPE_DATE = 'Date';
	const TYPE_DATE_TIME = 'DateTime';
	const TYPE_EMAIL = 'Email';
	const TYPE_URL = 'Url';
	const TYPE_TEL = 'Tel';
	const TYPE_SEARCH = 'Search';


	//TODO: range
	//TODO: month
	//TODO: week
	//TODO: time
	//TODO: color

	//TODO: const TYPE_REGISTRATION_USER_NAME = 'RegistrationUserName'
	//TODO: const TYPE_REGISTRATION_SEARCH = 'RegistrationEmail'
	//TODO: const TYPE_REGISTRATION_PASSWORD = 'RegistrationPassword'
	const TYPE_PASSWORD = 'Password';

	const TYPE_TEXTAREA = 'Textarea';
	const TYPE_WYSIWYG = 'WYSIWYG';

	const TYPE_FILE = 'File';
	const TYPE_FILE_IMAGE = 'FileImage';

	const TYPE_SELECT = 'Select';
	const TYPE_MULTI_SELECT = 'MultiSelect';

	const TYPE_CHECKBOX = 'Checkbox';
	const TYPE_RADIO_BUTTON = 'RadioButton';


	const FORM_SENT_KEY = '_jet_form_sent_';
	const FORM_TAG = 'jet_form';
	const FORM_COMMON_ERROR_MESSAGE_TAG = 'common_error_message';

	const COMMON_ERROR_MESSAGE_KEY = '__common_message__';

	/**
	 * @var array
	 */
	public static $HTML_templates = [
		'table' => [
			'form_start' => '<table>',
			'form_end' => '</table>',
			'form_common_error_message_class' => 'form-error',
			'form_submit_button' => '\t<tr>\n\t\t<td colspan="2" align="center">\n\t\t\t<input type="submit" class="btn btn-primary"/>\n\t\t</td>\n\t</tr>',
			'field' => '\t<tr>\n\t\t<td valign="top">%LABEL%</td>\n\t\t<td>\n\t\t\t%FIELD%\n\t\t</td>\n\t</tr>',
			'field_error_msg' => '<div class="form-error">%ERROR_MSG%</div>',
			'field_required' => '<em class="form-required">*</em> %LABEL%',
		],
		'div' => [
			'form_start' => '\t<fieldset>',
			'form_end' => '\t</fieldset>',
			'form_common_error_message_class' => 'form-error',
			'form_submit_button' => '\t<input type="submit" class="btn btn-primary"/>',
			'field' => '\t\t<div class="form-group">\n\t\t\t%LABEL%\n\t\t\t%FIELD%\n\t\t</div>',
			'field_error_msg' => '<div class="form-error">%ERROR_MSG%</div>',
			'field_required' => '<em class="form-required">*</em> %LABEL%',
		]
	];

	/**
	 * Form name
	 * @var string $name
	 */	
	protected $name = '';

	/**
	 * @var string
	 */
	protected $container_ID = null;

	/**
	 * container_ID_prefix = container_ID ? container_ID.'_' : ''
	 *
	 * @var string
	 */
	protected $container_ID_prefix = null;

	/**
	 * Form ID (container_ID_prefix.name)
	 * @var string $name
	 */
	protected $ID = '';

	/**
	 * POST (default) or GET
	 *
	 * @var string
	 */
	protected $method = 'POST';
	
	/**
	 * Form fields
	 *
	 * @var Form_Field_Abstract[]
	 */
	protected $fields= [];
	
	/**
	 * @var bool
	 */
	protected $is_valid = false;

	/**
	 * @var Mvc_Layout
	 */
	protected $__layout;

	/**
	 * @var string
	 */
	protected $decorator = '';

	/**
	 * @var Data_Array
	 */
	protected $raw_data;

	/**
	 * One of $HTML_templates
	 *
	 * @var string
	 */
	protected $selected_HTML_template_name = 'table';

	/**
	 * Common error message (without field context)
	 *
	 * @var string
	 */
	protected $common_error_message = '';

	/**
	 * @var bool
	 */
	protected $do_not_translate_texts = false;

	/**
	 * @var string|null
	 */
	protected $custom_translator_namespace = null;

	/**
	 * @var Locale|null
	 */
	protected $custom_translator_locale = null;
	
	/**
	 * constructor
	 * 
	 * @param string $name
	 * @param Form_Field_Abstract[] $fields
	 * @param string $method - POST or GET (optional, default: POST)
	 */
	public function __construct( $name, array $fields, $method='POST' ) {
		$this->name = $name;			
		$this->method = $method;
		$this->setFields($fields);
	}

	/**
	 * Get form name
	 * @return string
	 */
	public function getName(){
		return $this->name;
	}


	/**
	 * Get form ID
	 *
	 * @return string
	 */
	public function getID() {
		if($this->container_ID===null) {
			if( ($current_page=Mvc::getCurrentPage()) ) {
				$this->container_ID = $current_page->getLayout()->getUIContainerID();
				if($this->container_ID) {
					$this->container_ID_prefix = $this->container_ID . '_';
				} else {
					$this->container_ID_prefix = '';
				}
			}

		}

		return $this->container_ID_prefix.$this->name;
	}


	/**
	 * set form fields
	 *
	 * @param Form_Field_Abstract[] $fields
	 *
	 * @throws Form_Exception
	 */
	public function setFields(array $fields) {
		$this->fields = [];
		
		foreach($fields as $field) {
			$this->addField($field);
		}
	}

	/**
	 * @param Form_Field_Abstract $field
	 */
	public function addField( Form_Field_Abstract $field ) {
		$field->setForm($this);

		$key=$field->getName();
		$field->setForm($this);
		$this->fields[$key]=$field;

	}

	/**
	 * returns language independent fields
	 *
	 * @param bool $as_multidimensional_array (optional, default: false)
	 * @return Form_Field_Abstract[]
	 */
	public function getFields( $as_multidimensional_array=false ){
		if($as_multidimensional_array) {
			$fields = new Data_Array();

			foreach( $this->fields as $field ) {
				$fields->set( $field->getName(), $field );
			}

			return $fields->getRawData();

		}

		return $this->fields;
	}

	/**
	 *
	 * @param string $name
	 *
	 * @throws Form_Exception
	 * @return Form_Field_Abstract
	 */
	public function getField($name) {
		if(!isset($this->fields[$name])) {
			throw new Form_Exception(
				'Unknown field \''.$name.'\'',
				Form_Exception::CODE_UNKNOWN_FIELD
			);
		}

		return $this->fields[$name];
	}


    /**
     * @param string $field_name
     */
    public function removeField( $field_name ) {
        if(isset($this->fields[$field_name])) {
            unset($this->fields[$field_name]);
        }
    }

    /**
	 * @param $name
	 * @param Form_Field_Abstract $field
	 */
	public function setField( $name, Form_Field_Abstract $field ) {
		$this->fields[$name] = $field;
		$field->setForm($this);
	}

	/**
	 * @param $name
	 * @return bool
	 */
	public function getFieldExists( $name ) {
		return isset($this->fields[$name]);
	}

	/**
	 * @throws Form_Exception
	 */
	public function checkFieldsHasErrorMessages() {
		foreach( $this->fields as $field ) {
			$required_error_codes = $field->getRequiredErrorCodes();

			foreach( $required_error_codes as $code ) {
				if(!$field->getErrorMessage($code)) {
					throw new Form_Exception('Form field error message is not set. Form:'.$this->name.' Field:'. $field->getName().' Error code:'.$code);
				}
			}
		}
	}


	/**
	 * catch values from input ($_POST is default)
	 * and return true if form sent ...
	 *
	 * @param array $data
	 * @param bool $force_catch
	 *
	 * @return bool
	 */
	public function catchValues( $data=null, $force_catch=false ) {
		$this->is_valid = false;
		
		if($data===null) {
			$data = $this->method=='GET' ? Http_Request::GET()->getRawData() : Http_Request::POST()->getRawData();
		}

		if($data===false) {
			$data = [];
		}

		if(!$data instanceof Data_Array) {
			$data = new Data_Array($data);
		}
			
		if(
			!$force_catch &&
			$data->getString(self::FORM_SENT_KEY)!=$this->name
		) {
			return false;
		}

		foreach($this->fields as $field) {
			$field->catchValue($data);
		}

		$this->raw_data = $data;

		return true;
	}

	/**
	 * @return Data_Array
	 */
	public function getRawData() {
		return $this->raw_data;
	}
	
	/**
	 * validate form values
	 *
	 * @return bool
	 */
	public function validateValues() {
		$this->checkFieldsHasErrorMessages();

		$this->common_error_message = '';
		$this->is_valid = true;
		foreach($this->fields as $field) {

			$callback = $field->getValidateDataCallback();
			if($callback) {
				if(!$callback( $field )) {
					$this->is_valid = false;
				}

				continue;
			}

			if(!$field->checkValueIsNotEmpty()) {
				$this->is_valid = false;
				continue;
			}
			
			if(!$field->validateValue()) {
				$this->is_valid = false;
			}

		}


		return $this->is_valid;
	}

	/**
	 * Force invalidate
	 */
	public function setIsNotValid() {
		$this->is_valid = false;
	}

	/**
	 *
	 * @return bool
	 */
	public function getIsValid() {
		return $this->is_valid;
	}

	/**
	 * @param $message
	 */
	public function setCommonErrorMessage( $message ) {
		$this->common_error_message = $message;
		$this->is_valid = false;
	}

	/**
	 *
	 * @return string
	 */
	public function getCommonErrorMessage() {
		return $this->common_error_message;
	}


	
	/**
	 * get all errors in form
	 * 
	 * @return array
	 */
	public function getAllErrors() {
		$result = [];

		if($this->common_error_message) {
			$result[self::COMMON_ERROR_MESSAGE_KEY] = $this->common_error_message;
		}
		
		foreach($this->fields as $key=>$field) {
			$last_error = $field->getLastErrorMessage();
			
			if($last_error) {
				$result[$key] = $last_error;
			}
		}
		
		return $result;
	}


	/**
	 * returns field values if form is valid otherwise false
	 *
	 * @param bool $escape_values - example: for database usage *
	 * @param bool $force_skip_is_valid
	 *
	 * @return array
	 */
	public function getValues( $escape_values = false, $force_skip_is_valid = false ) {
		if(!$this->is_valid && !$force_skip_is_valid) {
			return false;
		}
			
		$result = [];
		foreach($this->fields as $key=>$field) {

			$value = $field->getValue();
			
			if($escape_values) {
				if(is_string($value)) {
					$value = addslashes($value);
				} else {
					if(is_bool($value)) {
						$value = $value ? 1:0;
					}
				}
			}
			
			$result[$key] = $value;
		}
		
		return $result;
	}

    /**
     *
     * @return bool
     */
    public function catchData() {
        if(!$this->is_valid) {
            return false;
        }

        foreach($this->fields as $field) {
            if(!$field->catchData()) {
                return false;
            }
        }

        return true;
    }


	/**
	 * replace <jet_form_* magic tags by real HTML in given output of view
	 *
	 * @param string &$result
	 * @param Mvc_View $view
	 *
	 * @throws Form_Exception
	 * @internal param string $output
	 *
	 * @return string
	 */
	public function viewPostProcess( &$result, Mvc_View $view) {
		$this->checkFieldsHasErrorMessages();

		$this->__layout = $view->getLayout();

		$form_output_part = strstr( $result, '<'.static::FORM_TAG.' name="'.$this->name.'"' );
		
		if(!$form_output_part) {
			return;
		}

		Mvc::checkCurrentContentIsDynamic();
		
		$form_output_part_pos = strpos($form_output_part, '</'.static::FORM_TAG.'>' );
		
		if(!$form_output_part_pos) {
			throw new Form_Exception(
					'Parse error: '.static::FORM_TAG.' end tag missing...',
					Form_Exception::CODE_VIEW_PARSE_ERROR
				);
		}

		$form_output_part = substr($form_output_part, 0, $form_output_part_pos + strlen('</'.static::FORM_TAG.'>'));

		$form_output_part_replacement = $form_output_part;

		$tags = $this->_parseTags($form_output_part_replacement);

		foreach( $tags as $tag_data ) {
			/**
			 * @var Form_Parser_TagData $tag_data
			 */
			$replacement = '';

			switch( $tag_data->getTag() ) {
				case self::FORM_COMMON_ERROR_MESSAGE_TAG:
					$replacement = $this->_getReplacement_common_error_message($tag_data);
				break;
				case 'form':
					if($tag_data->getPropertyIsSet('id')) {
						throw new Form_Exception(
							'Parse error: Form \''.$this->name.'\' has set ID property! Please do not set ID property yourself. It will be done by parser with regard to container_ID. ',
							Form_Exception::CODE_VIEW_PARSE_ERROR
						);
					}

					$replacement = $this->_getReplacement_form($tag_data);
				break;
				default:
					if(isset($this->fields[$tag_data->getName()])) {
						$field = $this->fields[$tag_data->getName()];

						$replacement = $field->getReplacement( $tag_data );
					}
				break;
			}

			$form_output_part_replacement = str_replace($tag_data->getOriginalString(), $replacement, $form_output_part_replacement);

		}

		$form_output_part_replacement = str_replace('</'.static::FORM_TAG.'>', '</form>', $form_output_part_replacement);


		$result = str_replace(
					$form_output_part,
					$form_output_part_replacement,
					$result
				);
	}

	/**
	 * @param Form_Parser_TagData $tag_data
	 * @return string
	 *
	 */
	protected function _getReplacement_form( Form_Parser_TagData $tag_data ) {
		$tag_data->setProperty('id', $this->getID() );
		$tag_data->setProperty('name', $this->name );
		$tag_data->setProperty('method', ($this->method=='GET') ? 'GET' : 'POST' );

		$replacement = '<form ';
		foreach($tag_data->getProperties() as $property=>$val) {
			$replacement .= ' '.$property.'="'.Data_Text::htmlSpecialChars($val).'"';
		}

		$replacement .= '>'.JET_EOL;
		$replacement .= '<input type="hidden" name="'.self::FORM_SENT_KEY.'" value="'.Data_Text::htmlSpecialChars($this->name).'" />'.JET_EOL;

		return $replacement;
	}

	/**
	 * @param Form_Parser_TagData $tag_data
	 * @return string
	 */
	protected function _getReplacement_common_error_message( Form_Parser_TagData $tag_data ) {
		$replacement = '';

		if($this->common_error_message) {

			$replacement = '<div ';
			foreach($tag_data->getProperties() as $property=>$val) {
				$replacement .= ' '.$property.'="'.Data_Text::htmlSpecialChars($val).'"';
			}
			$replacement .= '>'.Data_Text::htmlSpecialChars($this->common_error_message).'</div>'.JET_EOL;
		}

		return $replacement;
	}
	
	
	/**
	 * parse jet_form* tags data from given string
	 * 
	 * @param string $form_output_part
	 * 
	 * @return Form_Parser_TagData[]
	 */
	protected function _parseTags( $form_output_part) {
		
		$result = [];
		
		$matches = [];
		if(preg_match_all('/<'.static::FORM_TAG.'([a-zA-Z_]*) ([^>]*)>/i', $form_output_part, $matches, PREG_SET_ORDER)) {

			foreach($matches as $match) {
				$result[] = new Form_Parser_TagData( $match );
			}
		}

		return $result;
	}

	/**
	 * @param string $template
	 *
	 * @return string
	 */
	public function helper_getBasicHTML( $template='div' ) {

		$this->selected_HTML_template_name = $template;

		$result = '<'.static::FORM_TAG.' name="'.$this->name.'">'.JET_EOL;
		$result .= '<'.static::FORM_TAG.'_'.static::FORM_COMMON_ERROR_MESSAGE_TAG.' class="'.$this->getTemplate_form_common_error_message_class().'"/>'.JET_EOL;
		$result .= $this->getTemplate_form_start();

		foreach($this->fields as $field) {
			$result .= $field->helper_getBasicHTML();
		}

		$result .= $this->getTemplate_form_end();

		$result .= '</'.static::FORM_TAG.'>';

		$result = str_replace('\n', JET_EOL, $result);
		$result = str_replace('\t', JET_TAB, $result);

		return $result;
	}

	/**
	 * @param string $template
	 */
	public function helper_showBasicHTML( $template='div' ) {
		Http_Headers::responseOK( [
			     'Content-type' => 'text/plain'
		]);

		echo $this->helper_getBasicHTML( $template );
		Application::end();
	}

	/**
	 * @return string
	 */
	public function getDecorator() {
		return $this->decorator;
	}

	/**
	 * @param string $decorator
	 *
	 * @return Form
	 */
	public function enableDecorator($decorator) {
		$this->decorator = $decorator;

		return $this;
	}

	/**
	 *
	 * @return Form
	 */
	public function disableDecorator() {
		$this->decorator = '';

		return $this;
	}

	/**
	 * @param Form_Field_Abstract $field
	 *
	 * @return Form_Decorator_Abstract|bool|null
	 */
	public function getDecoratorInstance( Form_Field_Abstract $field ) {
		if(!$this->decorator) {
			return false;
		}

		$field_type = explode('_',get_class($field));
		$field_type = $field_type[count($field_type)-1];
		return Form_Factory::getDecoratorInstance($this->decorator, $field_type, $this, $field);
	}

	/**
	 * @return Mvc_Layout
	 */
	public function getLayout() {
		return $this->__layout;
	}

	/**
	 * @return string
	 */
	public function getSelectedHTMLTemplateName() {
		return $this->selected_HTML_template_name;
	}

	/**
	 * @param string $HTML_template
	 */
	public function setSelectedHTMLTemplateName($HTML_template) {
		$this->selected_HTML_template_name = $HTML_template;
	}

	/**
	 * @param string $result
	 * @return string
	 */
	protected function getTemplate_setNlAndTab( $result ) {
		$result = str_replace('\n', JET_EOL, $result);
		$result = str_replace('\t', JET_TAB, $result);

		return $result;
	}

	/**
	 * @return string
	 */
	public function getTemplate_form_start() {
		return $this->getTemplate_setNlAndTab( static::$HTML_templates[$this->selected_HTML_template_name]['form_start'].JET_EOL );
	}

	/**
	 * @return string
	 */
	public function getTemplate_form_end() {
		return $this->getTemplate_setNlAndTab( static::$HTML_templates[$this->selected_HTML_template_name]['form_submit_button'].JET_EOL
			.static::$HTML_templates[$this->selected_HTML_template_name]['form_end'].JET_EOL );
	}

	/**
	 * @return string
	 */
	public function getTemplate_form_common_error_message_class() {
		return $this->getTemplate_setNlAndTab( static::$HTML_templates[$this->selected_HTML_template_name]['form_common_error_message_class'] );
	}



	/**
	 * @return string
	 */
	public function getTemplate_field() {
		return $this->getTemplate_setNlAndTab( static::$HTML_templates[$this->selected_HTML_template_name]['field'].JET_EOL );
	}

	/**
	 * @return string
	 */
	public function getTemplate_field_error_msg() {
		return $this->getTemplate_setNlAndTab( static::$HTML_templates[$this->selected_HTML_template_name]['field_error_msg'].JET_EOL );
	}

	/**
	 * @return string
	 */
	public function getTemplate_field_required() {
		return $this->getTemplate_setNlAndTab( static::$HTML_templates[$this->selected_HTML_template_name]['field_required'].JET_EOL );
	}


	/**
	 * Returns translation. Used by field, error messages and so on.
	 *
	 * @see Translator
	 *
	 * @param string $phrase
	 * @param array $data
	 *
	 * @return string
	 */
	public function getTranslation( $phrase, $data= []) {
		if(!$phrase) {
			return $phrase;
		}
		if($this->do_not_translate_texts) {
			return $phrase;
		}

		return Tr::_($phrase, $data, $this->custom_translator_namespace, $this->custom_translator_locale);
	}

	/**
	 * @param bool $do_not_translate_texts
	 */
	public function setDoNotTranslateTexts($do_not_translate_texts) {
		$this->do_not_translate_texts = $do_not_translate_texts;
	}

	/**
	 * @return bool
	 */
	public function getDoNotTranslateTexts() {
		return $this->do_not_translate_texts;
	}

	/**
	 * @param null|string $custom_translator_namespace
	 */
	public function setCustomTranslatorNamespace($custom_translator_namespace) {
		$this->custom_translator_namespace = $custom_translator_namespace;
	}

	/**
	 * @return null|string
	 */
	public function getCustomTranslatorNamespace() {
		return $this->custom_translator_namespace;
	}

	/**
	 * @param null|Locale $custom_translator_locale
	 */
	public function setCustomTranslatorLocale(Locale $custom_translator_locale) {
		$this->custom_translator_locale = $custom_translator_locale;
	}

	/**
	 * @return null|Locale
	 */
	public function getCustomTranslatorLocale() {
		return $this->custom_translator_locale;
	}

	/**
	 *
	 */
	public function __wakeup() {
		foreach($this->fields as $field) {
			$field->setForm($this);
		}
	}

}