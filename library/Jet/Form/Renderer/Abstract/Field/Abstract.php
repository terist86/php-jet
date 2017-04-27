<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Form_Renderer_Abstract_Field_Abstract
 * @package Jet
 */
abstract class Form_Renderer_Abstract_Field_Abstract extends Form_Renderer_Abstract_Tag
{

	/**
	 * @var string
	 */
	protected $_input_type = 'text';

	/**
	 * @var string
	 */
	protected $tag = 'input';

	/**
	 * @var bool
	 */
	protected $is_pair = false;

	/**
	 * @var bool
	 */
	protected $has_content = false;

	/**
	 * @var string
	 */
	protected $type = 'input';

	/**
	 * @var Form_Field_Abstract
	 */
	protected $_field;

	/**
	 * @var int
	 */
	protected $custom_width;

	/**
	 * @var string
	 */
	protected $custom_size;

    /**
     * @var string
     */
    protected $tag_id;

    /**
     * @var string
     */
    protected $tag_name_value;

	/**
	 *
	 * @param Form_Field_Abstract $form_field
	 */
	public function __construct(Form_Field_Abstract $form_field)
	{
		$this->_field = $form_field;
        $this->tag_name_value = $this->_field->getTagNameValue();
        $this->tag_id = $this->_field->getId();
	}

	/**
	 * @return Form_Field_Abstract
	 */
	public function formField()
	{
		return $this->_field;
	}


    /**
     * @return string
     */
    public function getTagId()
    {
        return $this->tag_id;
    }

    /**
     * @param string $tag_id
     */
    public function setTagId($tag_id)
    {
        $this->tag_id = $tag_id;
    }

    /**
     * @return string
     */
    public function getTagNameValue()
    {
        return $this->tag_name_value;
    }

    /**
     * @param string $tag_name_value
     */
    public function setTagNameValue($tag_name_value)
    {
        $this->tag_name_value = $tag_name_value;
    }

	/**
	 * @param int $width
	 *
	 * @return $this
	 */
	public function setWidth($width)
	{
		$this->custom_width = $width;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getWidth()
	{
		if($this->custom_width) {
			return $this->custom_width;
		}

		return $this->_field->getForm()->getDefaultFieldWidth();
	}

	/**
	 * @return string
	 */
	public function getSize()
	{
		if($this->custom_size) {
			return $this->custom_size;
		}

		return $this->_field->getForm()->getDefaultSize();
	}

	/**
	 * @param string $custom_size
	 *
	 * @return $this
	 */
	public function setSize($custom_size)
	{
		$this->custom_size = $custom_size;

		return $this;
	}



	/**
	 * @return string
	 */
	public function render() {
		$tag_options = [
            'type' => $this->_input_type,
			'id' => $this->tag_id,
			'name' => $this->tag_name_value,
			'value' => $this->_field->getValue(),
		];

		if(($placeholder=$this->_field->getPlaceholder())) {
			$tag_options['placeholder'] = $placeholder;
		}

		if($this->_field->getIsReadonly()) {
			$tag_options['readonly'] = 'readonly';
		}

		if($this->_field->getIsRequired()) {
			$tag_options['required'] = 'required';
		}

		if( ($regexp=$this->_field->getValidationRegexp()) ) {

			if($regexp[0]=='/') {
				$regexp = substr($regexp, 1);
				$regexp = substr($regexp, 0, strrpos($regexp, '/'));
			}

			$tag_options['pattern'] = $regexp;
		}

		return $this->generate( $tag_options );
	}

}