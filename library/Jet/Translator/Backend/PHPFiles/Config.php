<?php
/**
 *
 *
 *
 * Common database adapter config
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Translator
 * @subpackage Translator_Backend
 */
namespace Jet;

/**
 * Class Translator_Backend_PHPFiles_Config
 *
 * @JetConfig:section_is_obligatory = false
 */
class Translator_Backend_PHPFiles_Config extends Translator_Backend_Config_Abstract {

	/**
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:is_required = false
	 * @JetConfig:default_value = '%JET_DATA_PATH%/dictionaries/%TRANSLATOR_NAMESPACE%/%TRANSLATOR_LOCALE%.php'
	 * @JetConfig:form_field_label = 'Dictionaries storage path: '
	 * 
	 * @var string
	 */
	protected $dictionaries_path = '';

	/**
	 * @param $namespace
	 * @param Locale $locale
	 *
	 * @return string
	 */
	public function getDictionaryPath( $namespace, Locale $locale ) {

		return Data_Text::replaceSystemConstants( Data_Text::replaceData( $this->dictionaries_path, array(
			'TRANSLATOR_NAMESPACE' => str_replace( '\\','/', $namespace),
			'TRANSLATOR_LOCALE' => (string)$locale
		)));
	}

}