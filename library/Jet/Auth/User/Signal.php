<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2015 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Auth
 * @subpackage Auth_Role
 */
namespace Jet;

class Auth_User_Signal extends Application_Signals_Signal {

	/**
	 *
	 * @param Auth_User_Abstract $sender
	 * @param string $name
	 * @param array $data (optional)
	 */
	public function __construct( Auth_User_Abstract $sender, $name, array $data=array() ) {
		parent::__construct($sender, $name, $data);
	}

	/**
	 *
	 * @return Auth_User_Abstract
	 */
	public function getSender(){
		return $this->sender;
	}

	/**
	 * @return Auth_User_Abstract
	 */
	public function getUser() {
		return $this->data['user'];
	}

}