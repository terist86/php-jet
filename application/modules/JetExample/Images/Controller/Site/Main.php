<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Images;

use Jet\Mvc_Controller_Standard;
use Jet\Mvc_Page_Content_Interface;
use Jet\Mvc;
use Jet\Navigation_Breadcrumb;

/**
 *
 */
class Controller_Site_Main extends Mvc_Controller_Standard
{
	protected static $ACL_actions_check_map = [
		'default' => false,
	];
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

	/**
	 *
	 */
	public function initialize()
	{
	}

	/**
	 *
	 */
	public function default_Action()
	{

		/**
		 * @var Gallery $gallery
		 */
		$gallery_id = $this->getActionParameterValue( 'gallery_id', Gallery::ROOT_ID );
		$gallery = $this->getActionParameterValue( 'gallery' );

		if( !$gallery ) {
			$gallery = Gallery::get( $gallery_id );
		}

		$children = Gallery::getChildren( $gallery_id );

		$this->view->setVar( 'children', $children );
		$this->view->setVar( 'gallery', $gallery );

		$this->render( 'default' );
	}


	/**
	 * @param string                     $path
	 * @param Mvc_Page_Content_Interface $page_content
	 *
	 * @return bool
	 */
	public function parseRequestPath( $path, Mvc_Page_Content_Interface $page_content = null )
	{
		$gallery_id = Gallery::ROOT_ID;
		$gallery = null;


		$path_fragments = explode('/',$path);

		$URI = Mvc::getCurrentPage()->getURI();

		if( $path_fragments ) {

			foreach( $path_fragments as $pf ) {

				if( ( $_g = Gallery::getByTitle( rawurldecode( $pf ), $gallery_id ) ) ) {
					$gallery = $_g;
					$gallery_id = $gallery->getIdObject();
					$URI .= rawurlencode( $gallery->getTitle() ).'/';

					Navigation_Breadcrumb::addURL( $gallery->getTitle(), $URI );

				} else {
					return false;
				}

			}
		}

		$page_content->setControllerActionParameters(
			[
				'gallery_id' => $gallery_id, 'gallery' => $gallery,
			]
		);

		return true;
	}
}