<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Backend
 */
namespace Jet;

class DataModel_Backend_MySQL_Config extends DataModel_Backend_Config_Abstract {

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:form_field_type = Form::TYPE_SELECT
	 * @JetConfig:form_field_get_select_options_callback = ['DataModel_Backend_MySQL_Config', 'getDbConnectionsList']
     * @JetConfig:form_field_label = 'Connection - read: '
     * @JetConfig:form_field_error_messages = [Form_Field_Abstract::ERROR_CODE_EMPTY=>'Please select database connection', Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE=>'Please select database connection']
	 *
	 * @var string
	 */
	protected $connection_read = '';

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:form_field_type = Form::TYPE_SELECT
	 * @JetConfig:form_field_get_select_options_callback = ['DataModel_Backend_MySQL_Config', 'getDbConnectionsList']
     * @JetConfig:form_field_label = 'Connection - write: '
     * @JetConfig:form_field_error_messages = [Form_Field_Abstract::ERROR_CODE_EMPTY=>'Please select database connection', Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE=>'Please select database connection']
	 *
	 * @var string
	 */
	protected $connection_write= '';

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:default_value = 'InnoDB'
	 * @JetConfig:form_field_label = 'Engine: '
     * @JetConfig:form_field_error_messages = [Form_Field_Abstract::ERROR_CODE_EMPTY=>'Please specify table engine']
	 *
	 * @var string
	 */
	protected $engine= '';

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:default_value = 'utf8'
	 * @JetConfig:form_field_label = 'Default charset: '
     * @JetConfig:form_field_error_messages = [Form_Field_Abstract::ERROR_CODE_EMPTY=>'Please specify charset']
	 *
	 * @var string
	 */
	protected $default_charset= '';

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:default_value = 'utf8_general_ci'
	 * @JetConfig:form_field_label = 'Default collate: '
     * @JetConfig:form_field_error_messages = [Form_Field_Abstract::ERROR_CODE_EMPTY=>'Please specify default collate']
	 *
	 * @var string
	 */
	protected $collate= '';


	/**
	 * @return string
	 */
	public function getCollate() {
		return $this->collate;
	}

	/**
	 * @param string $collate
	 */
	public function setCollate($collate)
	{
		$this->collate = $collate;
	}

	/**
	 * @return string
	 */
	public function getConnectionRead() {
		return $this->connection_read;
	}

	/**
	 * @param string $connection_read
	 */
	public function setConnectionRead($connection_read)
	{
		$this->connection_read = $connection_read;
	}

	/**
	 * @return string
	 */
	public function getConnectionWrite() {
		return $this->connection_write;
	}

	/**
	 * @param string $connection_write
	 */
	public function setConnectionWrite($connection_write)
	{
		$this->connection_write = $connection_write;
	}

	/**
	 * @return string
	 */
	public function getDefaultCharset() {
		return $this->default_charset;
	}

	/**
	 * @param string $default_charset
	 */
	public function setDefaultCharset($default_charset)
	{
		$this->default_charset = $default_charset;
	}

	/**
	 * @return string
	 */
	public function getEngine() {
		return $this->engine;
	}

	/**
	 * @param string $engine
	 */
	public function setEngine($engine)
	{
		$this->engine = $engine;
	}

	/**
	 * @return array
	 */
	public static function getDbConnectionsList() {
		return Db_Config::getConnectionsList(Db::DRIVER_MYSQL);
	}
}