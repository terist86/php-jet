<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Config_Section
 * @package Jet
 */
abstract class Config_Section extends Config {
	/**
	 * @var array
	 */
	protected $_data;

	/** @noinspection PhpMissingParentConstructorInspection
	 *
	 * @param array $data
	 * @param Config $configuration
	 */
	public function __construct(array $data, Config $configuration=null ) {
		if($configuration) {
			$this->config_file_path = $configuration->getConfigFilePath();
			$this->soft_mode = $configuration->getSoftMode();
		}
		$this->_data = $data;
		$data = new Data_Array($this->_data);
		$this->setData( $data );
	}

}