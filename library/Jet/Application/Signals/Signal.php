<?php
/**
 *
 *
 *
 * Implementation of signal (message).
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>,
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Application
 * @subpackage Application_Signals
 */

namespace Jet;

class Application_Signals_Signal extends BaseObject {

	/**
	 * Instance of the sender
	 *
	 * @var Object|BaseObject_Interface
	 */
	protected $sender;

	/**
	 * Name of the signal
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Signal data
	 *
	 * @var mixed
	 */
	protected $data = [];

	/**
	 *
	 * @param BaseObject_Interface $sender
	 * @param string $name
	 * @param array $data (optional)
	 */
	public function __construct(BaseObject_Interface $sender, $name, array $data= []) {
		$this->sender = $sender;
		$this->name = $name;
		$this->data = $data;
	}

	/**
	 *
	 * @return BaseObject_Interface
	 */
	public function getSender(){
		return $this->sender;
	}

	/**
	 *
	 * @return string
	 */
	public function getName(){
		return $this->name;
	}

	/**
	 *
	 * @return mixed
	 */
	public function getData(){
		return $this->data;
	}
}