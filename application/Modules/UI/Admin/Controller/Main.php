<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\UI\Admin;

use Jet\Mvc_Controller_Default;

use Jet\Auth;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Mvc_Page_Content_Interface;

use Jet\Mvc_Page;

/**
 *
 */
class Controller_Main extends Mvc_Controller_Default
{

	/**
	 *
	 * @param  Mvc_Page_Content_Interface $content
	 */
	public function __construct( Mvc_Page_Content_Interface $content )
	{
		parent::__construct( $content );



		$GET = Http_Request::GET();

		if( $GET->exists( 'logout' ) ) {
			$this->logout_Action();
		}
	}


	/**
	 *
	 */
	public function logout_Action() : void
	{
		Auth::logout();

		Http_Headers::movedTemporary( Mvc_Page::get( Mvc_Page::HOMEPAGE_ID )->getURL() );
	}

	/**
	 *
	 */
	public function default_Action() : void
	{
		$this->render( 'default' );
	}

	/**
	 *
	 */
	public function breadcrumb_navigation_Action() : void
	{
		$this->render( 'breadcrumb_navigation' );
	}

	/**
	 *
	 */
	public function messages_Action() : void
	{
		$this->render( 'messages' );
	}

	/**
	 *
	 */
	public function main_menu_Action() : void
	{
		$this->render( 'main_menu' );
	}

}