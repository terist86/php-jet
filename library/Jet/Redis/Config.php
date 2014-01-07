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
 * @subpackage Redis_Config
 */
namespace Jet;

class Redis_Config extends Config_Application {

	/**
	 * @var string
	 */
	protected static $__config_data_path = "redis";

	/**
	 * @var array
	 */
	protected static $__config_properties_definition = array(

		"default_connection_name" => array(
			"form_field_label" => "Default connection:",
			"type" => self::TYPE_STRING,
			"description" => "Connection name for Redis::get() / Redis::getConnection() if connection name is not specified (must be one of the keys in 'connections')",
			"is_required" => true,
			"default_value" => "default",
			"form_field_type" => "Select",
			"form_field_get_select_options_callback" => array("Jet\\Redis_Config", "getConnectionsList")
		),

		"connections" => array(
			"type" => self::TYPE_CONFIG_LIST,
			"data_path" => "connections",
			"config_factory_class_name" => "Jet\\Redis_Factory",
			"config_factory_method_name" => "getConnectionConfigInstance"
		)
	);

	/**
	 * @var string
	 */
	protected $default_connection_name = "default";


	/**
	 *
	 * @var Config_Definition_Property_ConfigList
	 */
	protected $connections;


	/**
	 * Get connection configuration
	 *
	 * @param $connection_name
	 *
	 * @throws Redis_Exception
	 * @return Redis_Connection_Config_Abstract
	 */
	public function getConnection($connection_name){
		return $this->connections->getConfigurationListItem( $connection_name );
	}

	/**
	 * @return Redis_Connection_Config_Abstract[]
	 */
	public function getConnections() {
		return $this->connections->getAllConfigurationItems();
	}

	/**
	 * Returns connection name for Redis::get() / Refis::getConnection() if connection name is not specified (one of the keys in 'connections')
	 *
	 * @return string
	 */
	public function getDefaultConnectionName() {
		return $this->default_connection_name;
	}

	/**
	 * @param $connection_name
	 * @param Redis_Connection_Config_Abstract $connection_configuration
	 *
	 */
	public function addConnection( $connection_name, Redis_Connection_Config_Abstract $connection_configuration ) {
		$this->connections->addConfigurationItem( $connection_name, $connection_configuration );
	}

	/**
	 * @param $connection_name
	 *
	 */
	public function deleteConnection( $connection_name ) {
		$this->connections->deleteConfigurationItem( $connection_name );
	}

	/**
	 *
	 * @return array
	 */
	public static function getConnectionsList() {
		$i = new self(true);

		$connections = array();

		foreach( $i->getConnections() as $name=>$connection) {

			$connections[$name] = $name;
		}

		return array_combine($connections, $connections);
	}
}