<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\BaseObject;
use Jet\IO_File;
use Jet\MVC_Page_Content_Interface;
use Jet\MVC_Page_Interface;
use Jet\SysConf_Path;

/**
 *
 */
class PageStaticContent extends BaseObject
{
	/**
	 * @param MVC_Page_Interface $page
	 * @param MVC_Page_Content_Interface|null $page_content
	 *
	 * @return string
	 */
	public static function get( MVC_Page_Interface $page, MVC_Page_Content_Interface $page_content = null ) : string
	{

		$root_dir = SysConf_Path::getApplication() . 'texts/staticContent/';

		if(
			$page_content &&
			($text_id = $page_content->getParameter( 'text_id' ))
		) {
			$file_path = $root_dir . $page->getLocale() . '/' . $text_id . '.html';
		} else {
			$file_path = $root_dir . $page->getLocale() . '/' . $page->getBase()->getId() . '/' . $page->getId() . '.html';
		}

		if( !IO_File::exists( $file_path ) ) {
			return '';
		}

		return IO_File::read( $file_path );
	}
}