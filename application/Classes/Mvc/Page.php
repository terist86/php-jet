<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Auth_Role;
use Jet\Locale;
use Jet\Mvc_Page as Jet_Mvc_Page;
use Jet\Application_Modules;
use Jet\Mvc;
use Jet\Mvc_Page_Exception;
use Jet\Mvc_Site_Interface;
use Jet\Mvc_Factory;
use Jet\Mvc_Layout;
use Jet\Auth;
use Jet\Tr;

/**
 *
 */
class Mvc_Page extends Jet_Mvc_Page
{
	const CHANGE_PASSWORD_ID = '_change_password_';
	const ADMIN_HOMEPAGE_ID = 'admin';
	const REST_HOMEPAGE_ID = 'rest';

	/**
	 * @var bool
	 */
	//TODO: zvazit
	protected $is_dialog = false;

	/**
	 * @var bool
	 */
	//TODO: zvazit
	protected $is_system_page = false;

	/**
	 * @var bool
	 */
	//TODO: presunout do jadra
	protected $is_rest_api_hook = false;
	/**
	 * @var string
	 */
	protected $icon = '';

	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale             $locale
	 *
	 * @throws Mvc_Page_Exception
	 */
	public static function loadCustomPages( Mvc_Site_Interface $site, Locale $locale )
	{

		parent::loadCustomPages( $site, $locale );

		$modules = Application_Modules::getActivatedModulesList();


		foreach( $modules as $manifest ) {
			/**
			 * @var Application_Module_Manifest $manifest
			 */

			foreach( $manifest->getAdminSections() as $page_id => $page_data ) {
				if( !isset( $page_data['layout_script_name'] ) ) {
					$page_data['layout_script_name'] = 'default';
				}

				static::addAdminPage( $locale, static::ADMIN_HOMEPAGE_ID, $page_id, $page_data, $manifest );
			}

			foreach( $manifest->getAdminDialogs() as $page_id => $page_data ) {
				$page_id = 'dialog_'.$page_id;
				$page_data['is_dialog'] = true;
				if( !isset( $page_data['layout_script_name'] ) ) {
					$page_data['layout_script_name'] = 'dialog';
				}

				$page_data['relative_path_fragment'] = 'dialog-'.$page_data['relative_path_fragment'];

				static::addAdminPage( $locale, static::ADMIN_HOMEPAGE_ID, $page_id, $page_data, $manifest );
			}

			if($manifest->hasRestAPI()) {
				static::addRestHook( $locale, static::REST_HOMEPAGE_ID, $manifest );
			}

		}
	}

	/**
	 * @param Locale                      $locale
	 * @param string                      $parent_page_id
	 * @param string                      $page_id
	 * @param array                       $page_data
	 * @param Application_Module_Manifest $module_manifest
	 *
	 * @throws Mvc_Page_Exception
	 */
	protected static function addAdminPage( Locale $locale, $parent_page_id, $page_id, array $page_data, Application_Module_Manifest $module_manifest )
	{

		/**
		 * @var Mvc_Page $parent_page
		 */
		$parent_page = Mvc_Page::get( $parent_page_id, $locale );
		if( !$parent_page ) {
			return;
		}

		$parent_page->setIsSystemPage( true );

		$page_data['id'] = $page_id;

		$page_data['title'] = empty( $page_data['title'] ) ? $module_manifest->getLabel() : $page_data['title'];
		$page_data['title'] = Tr::_( $page_data['title'], [], $module_manifest->getName(), $locale );

		if( empty( $page_data['menu_title'] ) ) {
			$page_data['menu_title'] = $page_data['title'];
		} else {
			$page_data['menu_title'] = Tr::_( $page_data['menu_title'], [], $module_manifest->getName(), $locale );
		}

		if( empty( $page_data['breadcrumb_title'] ) ) {
			$page_data['breadcrumb_title'] = $page_data['title'];
		} else {
			$page_data['breadcrumb_title'] = Tr::_(
				$page_data['breadcrumb_title'], [], $module_manifest->getName(), $locale
			);
		}

		if( empty( $page_data['icon'] ) ) {
			$page_data['icon'] = '';
		}


		$is_dialog = !empty( $page_data['is_dialog'] );
		if( $is_dialog ) {
			$is_system_page = false;
		} else {
			$is_system_page = !empty( $page_data['is_system_page'] );
		}


		if( $is_dialog ) {
			$action = substr( $page_id, 7 );
		} else {
			$action = empty( $page_data['action'] ) ? 'default' : $page_data['action'];
		}

		if( isset( $page_data['action'] ) ) {
			unset( $page_data['action'] );
		}


		$page = static::createPageByData( Mvc::getCurrentSite(), $locale, $page_data, $parent_page );
		$page->setIsAdminUI( true );
		$page->setCustomLayoutsPath( $parent_page->getCustomLayoutsPath() );


		$content = Mvc_Factory::getPageContentInstance();
		$content->setId( $page_id.'_'.$action );
		$content->setModuleName( $module_manifest->getName() );
		$content->setControllerAction( $action );
		$content->setOutputPosition( Mvc_Layout::DEFAULT_OUTPUT_POSITION );

		if( $is_dialog ) {
			$content->setCustomController( 'Dialogs' );
		}
		if( $is_system_page ) {
			$content->setCustomController( 'SystemPages' );
		}

		$page->setContent( [ $content ] );

		static::appendPage( $page );

	}

	/**
	 * @param Locale                      $locale
	 * @param string                      $parent_page_id
	 * @param Application_Module_Manifest $module_manifest
	 *
	 */
	protected static function addRestHook( Locale $locale, $parent_page_id, Application_Module_Manifest $module_manifest )
	{

		/**
		 * @var Mvc_Page $parent_page
		 */
		$parent_page = Mvc_Page::get( $parent_page_id, $locale );
		if( !$parent_page ) {
			return;
		}

		$parent_page->setIsRestApiHook( true );

		$content = Mvc_Factory::getPageContentInstance();
		$content->setId( $module_manifest->getName() );
		$content->setModuleName( $module_manifest->getName() );
		$content->setControllerAction( false );
		$content->setCustomController( 'REST' );

		$parent_page->addContent( $content );


	}

	/**
	 * @return string
	 */
	public function getIcon()
	{
		return $this->icon;
	}

	/**
	 * @param string $icon
	 */
	public function setIcon( $icon )
	{
		$this->icon = $icon;
	}

	/**
	 * @return boolean
	 */
	public function getIsRestApiHook()
	{
		return $this->is_rest_api_hook;
	}

	/**
	 * @param boolean $is_rest_api_hook
	 */
	public function setIsRestApiHook( $is_rest_api_hook )
	{
		$this->is_rest_api_hook = $is_rest_api_hook;
	}

	/**
	 * @return bool
	 */
	public function getAccessAllowed()
	{

		if( !$this->getIsAdminUI() ) {
			return parent::getAccessAllowed();
		}

		if( $this->getIsDialog()||$this->getIsSystemPage() ) {
			return true;
		}

		if( Auth::getCurrentUserHasPrivilege( Auth_Role::PRIVILEGE_VISIT_PAGE, $this->getId() ) ) {
			return true;
		}

		return false;

	}

	/**
	 * @return bool
	 */
	public function getIsDialog()
	{
		return $this->is_dialog;
	}

	/**
	 * @param bool $is_dialog
	 */
	public function setIsDialog( $is_dialog )
	{
		$this->is_dialog = $is_dialog;
	}

	/**
	 * @return bool
	 */
	public function getIsSystemPage()
	{
		return $this->is_system_page;
	}

	/**
	 * @param bool $is_system_page
	 */
	public function setIsSystemPage( $is_system_page )
	{
		$this->is_system_page = $is_system_page;
	}

}