<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\REST;

use JetApplication\Application_Web;


/**
 *
 */
class Test_Gallery_PostInvalid extends Test_Abstract
{


	/**
	 * @return string
	 */
	protected function _getTitle(): string
	{
		return 'Add (POST) - invalid (error simulation)';
	}

	/**
	 *
	 */
	public function test(): void
	{
		$data = [
			'parent_id' => 'xxxxx',
			'localized' =>
				[
				]
		];

		foreach( Application_Web::getBase()->getLocales() as $locale_str => $locale ) {
			$data['localized'][$locale_str] = [
				'title' => ''
			];
		}

		$this->client->post( 'gallery', $data );

	}
}
