<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Application
 */
namespace Jet;

/**
 * Class Application
 *
 * @JetApplication_Signals:signal = '/application/started'
 * @JetApplication_Signals:signal = '/application/ended'
 *
 */
class Application extends Object {

	/**
	 * @var bool
	 */
	protected static $do_not_end = false;

	/**
	 * @var string
	 */
	protected static $config_file_path;

	/**
	 * @var Application_Config
	 */
	protected static $config = null;

	/**
	 * @return string
	 */
	public static function getConfigFilePath()
	{
		if(!self::$config_file_path) {
			static::$config_file_path = JET_CONFIG_PATH . JET_APPLICATION_CONFIGURATION_NAME.'.php';
		}

		return self::$config_file_path;
	}

	/**
	 * @param string $config_file_path
	 */
	public static function setConfigFilePath($config_file_path)
	{
		self::$config_file_path = $config_file_path;
	}



	/**
	 * Start application
	 *
	 * @static
	 *
	 * @throws Application_Exception
	 */
	public static function start(){

		Debug_Profiler::MainBlockStart('Application init');


		Debug_Profiler::blockStart('Http request init');
			Http_Request::initialize( JET_HIDE_HTTP_REQUEST );
		Debug_Profiler::blockEnd('Http request init');

		Debug_Profiler::MainBlockEnd('Application init');

		$app = new self();
		$app->sendSignal('/application/started');

	}

	/**
	 * @return Application_Config|null
	 */
	public static function getConfig() {
		if(!static::$config) {
			Debug_Profiler::blockStart('Configuration init');
			static::$config = new Application_Config();
			Debug_Profiler::blockEnd('Configuration init');
		}

		return static::$config;
	}

	/**
	 * @static
	 *
	 */
	public static function end(){
		$app = new self();
		$app->sendSignal('/application/ended');

		if(!static::$do_not_end) {
			exit();
		}
	}

	/**
	 * Useful for tests
	 *
	 */
	public static function doNotEnd() {
		static::$do_not_end = true;
	}

	/**
	 * @static
	 *
	 * @return bool
	 */
	public static function getIsInDevelMode(){
		return defined('JET_DEVEL_MODE') && JET_DEVEL_MODE;
	}

}