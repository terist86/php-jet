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
 */
namespace Jet;

/**
 * Class Db_Connection_Config_Abstract
 *
 * @JetFactory:class = null
 * @JetFactory:method = null
 * @JetFactory:mandatory_parent_class = 'Jet\Db_Connection_Config_Abstract'
 */
abstract class Db_Connection_Config_Abstract extends Config_Section {


	/**
	 * @JetConfig:form_field_label = 'Connection name'
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:default_value = 'default'
	 * @JetConfig:is_required = true
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * @JetConfig:form_field_label = 'Driver'
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:description = 'PDO driver'
	 * @JetConfig:default_value = 'mysql'
	 * @JetConfig:is_required = true
	 * @JetConfig:form_field_type = 'Select'
	 * @JetConfig:form_field_get_select_options_callback = ['Jet\Db_Connection_PDO_Config', 'getPDODrivers']
	 *
	 * @var string
	 */
	protected $driver;

	/**
	 * @JetConfig:form_field_label = 'DSN'
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:default_value = ''
	 * @JetConfig:is_required = true
	 *
	 * @var string
	 */
	protected $DSN;

	/**
	 * @JetConfig:form_field_label = 'Username'
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:default_value = null
	 * @JetConfig:is_required = false
	 *
	 * @var string
	 */
	protected $username;

	/**
	 * @JetConfig:form_field_label = 'Password'
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:default_value = null
	 * @JetConfig:is_required = false
	 *
	 * @var string
	 */
	protected $password;

	/**
	 * @return array
	 */
	public static function getPDODrivers() {
		$drivers = \PDO::getAvailableDrivers();

		return array_combine( $drivers, $drivers );
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getDriver() {
		return $this->driver;
	}

	/**
	 * Get authorization user name
	 *
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * Get authorization password
	 *
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @return string
	 */
	public function getDsn() {
		return $this->driver.':'.$this->DSN;
	}
}