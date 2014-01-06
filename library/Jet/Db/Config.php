<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Db
 * @subpackage Db_Config
 */
namespace Jet;

class Db_Config extends Config_Application {
	/**
	 * @var string
	 */
	protected static $__config_data_path = "database";

	/**
	 * @var array
	 */
	protected static $__config_properties_definition = array(

		"default_connection_name" => array(
			"form_field_label" => "Default connection:",
			"type" => self::TYPE_STRING,
			"description" => "Connection name for Db::get() / Db::getConnection() if connection name is not specified (must be one of the keys in 'connections')",
			"is_required" => true,
			"default_value" => "default",
			"form_field_type" => "Select",
			"form_field_get_select_options_callback" => array("Jet\\Db_Config", "getConnectionsList")
		),

		"connections" => array(
			"type" => self::TYPE_CONFIG_LIST,
			"data_path" => "connections",
			"config_factory_class_name" => "Jet\\Db_Factory",
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
	 * @throws Db_Exception
	 * @return Db_Connection_Config_Abstract
	 */
	public function getConnection($connection_name){
		return $this->connections->getConfigurationListItem( $connection_name );
	}

	/**
	 * @return Db_Connection_Config_Abstract[]
	 */
	public function getConnections() {
		return $this->connections->getAllConfigurationItems();
	}

	/**
	 * Returns connection name for Db::get() / Db::getConnection() if connection name is not specified (one of the keys in 'connections')
	 *
	 * @return string
	 */
	public function getDefaultConnectionName() {
		return $this->default_connection_name;
	}

	/**
	 * @param $connection_name
	 * @param Db_Connection_Config_Abstract $connection_configuration
	 *
	 */
	public function addConnection( $connection_name, Db_Connection_Config_Abstract $connection_configuration ) {
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

		$connections = array_keys($i->getConnections());

		return array_combine($connections, $connections);
	}
}