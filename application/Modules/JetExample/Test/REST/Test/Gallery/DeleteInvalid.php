<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplicationModule\JetExample\Test\REST;


/**
 *
 */
class Test_Gallery_DeleteInvalid extends Test_Abstract
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

		$this->client->delete('gallery/'.$id);

	}
}
