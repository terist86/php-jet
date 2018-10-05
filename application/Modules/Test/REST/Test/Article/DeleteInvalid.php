<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplicationModule\Test\REST;


/**
 *
 */
class Test_Article_DeleteInvalid extends Test_Abstract
{

	/**
	 * @return string
	 */
	protected function _getTitle()
	{
		return 'Delete - invalid (error simulation)';
	}

	/**
	 *
	 */
	public function test()
	{
		$id = 'unknown-unknown';

		$this->client->delete('article/'.$id);

	}
}