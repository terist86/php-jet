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
 * @category Jet
 * @package JetML
 */
namespace Jet;

class JetML_Widget_Div extends JetML_Widget_Abstract {

	/**
	 *
	 * @return \DOMElement
	 */
	public function getReplacement() {

		$attributes = $this->getNodeAttributes();

		return $this->createNode('div', true, $attributes);

	}

}