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
 * @abstract
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Sites
 */
namespace Jet;

/**
 * Class Mvc_Sites_Site_ID_Default
 *
 * @JetFactory:class = 'Jet\\Mvc_Factory'
 * @JetFactory:method = 'getSiteIDInstance'
 * @JetFactory:mandatory_parent_class = 'Jet\\Mvc_Sites_Site_ID_Abstract'
 */
class Mvc_Sites_Site_ID_Default extends Mvc_Sites_Site_ID_Abstract {

	/**
	 * @param string $ID
	 */
	public function setSiteID( $ID ) {
		$this->values['ID'] = $ID;
	}

	/**
	 * @return string
	 */
	public function getSiteID() {
		return $this->values['ID'];
	}

	/**
	 * Generate unique ID
	 *
	 * @param DataModel $data_model_instance
	 * @param bool $called_after_save (optional, default = false)
	 * @param mixed $backend_save_result  (optional, default = null)
	 *
	 */
	public function generate( DataModel $data_model_instance, $called_after_save = false, $backend_save_result = null ) {

		if(!$this->values['ID']) {
			/**
			 * @var Mvc_Sites_Site_Abstract $data_model_instance
			 */
			$this->generateNameID( $data_model_instance, 'ID', $data_model_instance->getName() );
		}

	}

}