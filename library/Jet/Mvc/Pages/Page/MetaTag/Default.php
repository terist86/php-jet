<?php
/**
 *
 *
 *
 * Class describes one meta tag
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Pages
 */
namespace Jet;

/**
 * Class Mvc_Pages_Page_MetaTag_Default
 *
 * @JetDataModel:name = 'Jet_Mvc_Pages_Page_MetaTag'
 * @JetDataModel:parent_model_class_name = 'Jet\\Mvc_Pages_Page_Default'
 */
class Mvc_Pages_Page_MetaTag_Default extends Mvc_Pages_Page_MetaTag_Abstract {

	/**
	 * @var string
	 */
	protected $Jet_Mvc_Pages_Page_ID = '';
	/**
	 * @var string
	 */
	protected $Jet_Mvc_Pages_Page_locale = '';
	/**
	 * @var string
	 */
	protected $Jet_Mvc_Pages_Page_site_ID = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_ID
	 * @JetDataModel:is_ID = true
	 *
	 * @var string
	 */
	protected $ID = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 *
	 * @var string
	 */
	protected $attribute = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 *
	 * @var string
	 */
	protected $attribute_value = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 *
	 * @var string
	 */
	protected $content = '';

	/**
	 * @param string $content (optional)
	 * @param string $attribute (optional)
	 * @param string $attribute_value (optional)
	 */
	public function __construct($content='', $attribute='', $attribute_value='') {
		if($content) {
			$this->generateID();

			$this->content = $content;
			$this->attribute = $attribute;
			$this->attribute_value = $attribute_value;
		}
	}

	/**
	 * @return string
	 */
	public function  toString() {
		if($this->attribute) {
			return '<meta '.$this->attribute.'="'.htmlspecialchars($this->attribute_value).'" content="'.htmlspecialchars($this->content).'" />';
		} else {
			return '<meta content="'.htmlspecialchars($this->content).'" />';
		}
	}

	/**
	 * @return string
	 */
	public function getAttribute() {
		return $this->attribute;
	}

	/**
	 * @param string $attribute
	 */
	public function setAttribute($attribute) {
		$this->attribute = $attribute;
	}

	/**
	 * @return string
	 */
	public function getAttributeValue() {
		return $this->attribute_value;
	}

	/**
	 * @param string $attribute_value
	 */
	public function setAttributeValue($attribute_value) {
		$this->attribute_value = $attribute_value;
	}

	/**
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @param string $content
	 */
	public function setContent($content) {
		$this->content = $content;
	}
}