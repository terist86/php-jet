<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Config
 */
namespace Jet;

/**
 * Class DataModel_Config
 *
 * @JetConfig:data_path = 'data_model'
 */
class DataModel_Config extends Application_Config {

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:default_value = 'MySQL'
	 * @JetConfig:form_field_type = Form::TYPE_SELECT
	 * @JetConfig:form_field_get_select_options_callback = ['DataModel_Config', 'getBackendTypesList']
     * @JetConfig:form_field_label = 'Default backend type: '
     * @JetConfig:form_field_error_messages = ['empty'=>'Please select backend type', 'invalid_value'=>'Please select backend type']
	 *
	 * @var string
	 */
	protected $backend_type;

	/**
	 * @JetConfig:type = Config::TYPE_BOOL
	 * @JetConfig:default_value = true
	 * @JetConfig:form_field_label = 'Enable data history:'
	 * 
	 * @var bool
	 */
	protected $history_enabled;

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:default_value = 'MySQL'
	 * @JetConfig:form_field_type = Form::TYPE_SELECT
	 * @JetConfig:form_field_get_select_options_callback = ['DataModel_Config', 'getHistoryBackendTypesList']
     * @JetConfig:form_field_label = 'History backend type: '
     * @JetConfig:form_field_error_messages = ['empty'=>'Please select history backend type', 'invalid_value'=>'Please select history backend type']
	 *
	 * @var string
	 */
	protected $history_backend_type;

	/**
	 * @JetConfig:type = Config::TYPE_BOOL
	 * @JetConfig:default_value = true
	 * @JetConfig:form_field_label = 'Enable data cache:'
	 * 
	 * @var bool
	 */
	protected $cache_enabled;

	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:is_required = true
	 * @JetConfig:default_value = 'MySQL'
	 * @JetConfig:form_field_label = 'Cache backend type: '
	 * @JetConfig:form_field_type = Form::TYPE_SELECT
	 * @JetConfig:form_field_get_select_options_callback = ['DataModel_Config', 'getCacheBackendTypesList']
     * @JetConfig:form_field_error_messages = ['empty'=>'Please select cache backend type', 'invalid_value'=>'Please select cache backend type']
	 *
	 * @var string
	 */
	protected $cache_backend_type;

	/**
	 * @return string
	 */
	public function getBackendType() {
		return $this->backend_type;
	}

	/**
	 * @return string
	 */
	public function getCacheBackendType() {
		return $this->cache_backend_type;
	}

	/**
	 * @return boolean
	 */
	public function getCacheEnabled() {
		return $this->cache_enabled;
	}

	/**
	 * @return string
	 */
	public function getHistoryBackendType() {
		return $this->history_backend_type;
	}

	/**
	 * @return boolean
	 */
	public function getHistoryEnabled() {
		return $this->history_enabled;
	}

	/**
	 * @return array
	 */
	public static function getBackendTypesList() {
		return static::getAvailableHandlersList( JET_LIBRARY_PATH.'Jet/DataModel/Backend/' );
	}

	/**
	 * @return array
	 */
	public static function getHistoryBackendTypesList() {
		return static::getAvailableHandlersList( JET_LIBRARY_PATH.'Jet/DataModel/History/Backend/' );
	}


	/**
	 * @return array
	 */
	public static function getCacheBackendTypesList() {
		return static::getAvailableHandlersList( JET_LIBRARY_PATH.'Jet/DataModel/Cache/Backend/' );
	}
}