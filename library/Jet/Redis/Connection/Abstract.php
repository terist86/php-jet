<?php
/**
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Redis
 */
namespace Jet;

abstract class Redis_Connection_Abstract extends \Redis implements Object_Interface {

	use Object_Trait;
	use Object_Trait_MagicSleep;
	//use Object_Trait_MagicGet;
	//use Object_Trait_MagicSet;


	/**
	 * @var string|null
	 */
	protected static $__factory_class_name = null;

	/**
	 * @var string|null
	 */
	protected static $__factory_class_method_name = null;

	/**
	 * @var string
	 */
	public static $__factory_must_be_instance_of_class_name = "Jet\\Redis_Connection_Abstract";


	/**
	 *
	 * @var Redis_Connection_Config_Abstract
	 */
	protected $config = null;

	/**
	 * @param Redis_Connection_Config_Abstract $config
	 *
	 * @throws Redis_Exception
	 */
	public function __construct( Redis_Connection_Config_Abstract $config ) {

		$this->config = $config;

		parent::__construct();

		if(!$this->connect( $this->config->getHost(), $this->config->getPort() )) {
			throw new Redis_Exception(
				"Unable to connect Redis '{$this->config->getHost()}:{$this->config->getPort()}' ",
				Redis_Exception::CODE_UNABLE_TO_CONNECT
			);
		}
	}

	/**
	 * Close connection on exit
	 */
	public function __destruct() {
		try {
			$this->disconnect();
		} catch(Exception $e){}
	}

	/**
	 *
	 * @return Redis_Connection_Config_Abstract
	 */
	public function getConfig(){
		return $this->config;
	}

	/**
	 *
	 */
	abstract public function disconnect();

	/**
	 *
	 * @param string $key
	 *
	 */
	public function __get( $key ) {
	}

	/**
	 *
	 * @param string $key
	 *
	 */
	public function __set( $key, $value ) {
		$this->{$key} = $value;
	}

}