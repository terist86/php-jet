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

class Redis_Connection_Default extends Redis_Connection_Abstract {

	/**
	 *
	 */
	public function disconnect() {
		$this->close();
	}
}