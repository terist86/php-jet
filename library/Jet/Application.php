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
	 * @var Application_Config
	 */
	protected static $config = null;

	/**
	 * Start application
	 *
	 * @static
	 *
	 * @throws Application_Exception
	 */
	public static function start(){

		Debug_Profiler::MainBlockStart('Application init');


		$config_file_path = JET_CONFIG_PATH . JET_APPLICATION_CONFIGURATION_NAME.'.php';

		Debug_Profiler::blockStart('Configuration init');
			Config::setApplicationConfigFilePath( $config_file_path );
			static::getConfig();
		Debug_Profiler::blockEnd('Configuration init');

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
			static::$config = new Application_Config();
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