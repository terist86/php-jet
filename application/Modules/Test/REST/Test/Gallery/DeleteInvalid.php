<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\REST;


/**
 *
 */
class Test_Gallery_DeleteInvalid extends Test_Abstract
{

	/**
	 * @return string
	 */
	protected function _getTitle(): string
	{
		return 'Delete - invalid (error simulation)';
	}

	/**
	 *
	 */
	public function test(): void
	{
		$id = 'unknown-unknown';

		$this->client->delete( 'gallery/' . $id );

	}
}
