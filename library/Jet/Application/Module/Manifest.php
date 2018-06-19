<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Application_Module_Manifest extends BaseObject
{

	/**
	 * @var string
	 */
	protected static $manifest_file_name = 'manifest.php';

	/**
	 *
	 * @var string
	 */
	protected $_name = '';

	//--------------------------------------------------------------------------
	/**
	 *
	 * @var int
	 */
	protected $API_version = 201701;

	/**
	 * @var string
	 */
	protected $vendor = '';

	/**
	 * @var string
	 */
	protected $version = '';


	/**
	 *
	 * @var string
	 */
	protected $label = '';

	/**
	 *
	 * @var string
	 */
	protected $description = '';

	/**
	 * @var bool
	 */
	protected $is_mandatory = false;

	//--------------------------------------------------------------------------


	/**
	 * @var array
	 */
	protected $pages = [];

	/**
	 * @var array
	 */
	protected $menu_items = [];

	//--------------------------------------------------------------------------


	/**
	 * @return string
	 */
	public static function getManifestFileName()
	{
		return static::$manifest_file_name;
	}

	/**
	 * @param string $manifest_file_name
	 */
	public static function setManifestFileName( $manifest_file_name )
	{
		static::$manifest_file_name = $manifest_file_name;
	}


	/**
	 * @param string $module_name (optional)
	 *
	 * @throws Application_Modules_Exception
	 */
	public function __construct( $module_name = null )
	{
		if( !$module_name ) {
			return;
		}

		$this->_name = $module_name;

		$manifest_data = $this->readManifestData();
		$this->checkManifestData( $manifest_data );
		$this->setupProperties( $manifest_data );


	}

	/**
	 * @return array
	 *
	 * @throws Application_Modules_Exception
	 */
	protected function readManifestData()
	{
		$module_dir = $this->getModuleDir();

		if( !IO_Dir::exists( $module_dir ) ) {
			throw new Application_Modules_Exception(
				'Directory \''.$module_dir.'\' does not exist',
				Application_Modules_Exception::CODE_MODULE_DOES_NOT_EXIST
			);
		}


		$manifest_file = $module_dir.static::$manifest_file_name;

		if( !IO_File::isReadable( $manifest_file ) ) {
			throw new Application_Modules_Exception(
				'Module manifest file \''.$manifest_file.'\' does not exist or is not readable. ',
				Application_Modules_Exception::CODE_MANIFEST_IS_NOT_READABLE
			);
		}

		/** @noinspection PhpIncludeInspection */
		$manifest_data = require $manifest_file;

		return $manifest_data;
	}

	/**
	 * @param array $manifest_data
	 *
	 * @throws Application_Modules_Exception
	 */
	protected function checkManifestData( $manifest_data )
	{
		if( !is_array( $manifest_data ) ) {
			throw new Application_Modules_Exception(
				'Manifest data must be array (Module: \''.$this->_name.'\')',
				Application_Modules_Exception::CODE_MANIFEST_NONSENSE
			);
		}

		if( empty( $manifest_data['API_version'] ) ) {
			throw new Application_Modules_Exception(
				'Required API version not set! (\'API_version\' array key does not exist, or is empty) (Module: \''.$this->_name.'\')',
				Application_Modules_Exception::CODE_MANIFEST_NONSENSE
			);
		}

		if( empty( $manifest_data['label'] ) ) {
			throw new Application_Modules_Exception(
				'Module label not set! (\'label\' array key does not exist, or is empty) (Module: \''.$this->_name.'\')',
				Application_Modules_Exception::CODE_MANIFEST_NONSENSE
			);
		}



	}

	/**
	 * Sets the values ​​according to the manifest data
	 *
	 * @param array $manifest_data
	 *
	 * @throws Application_Modules_Exception
	 */
	protected function setupProperties( array $manifest_data )
	{

		foreach( $manifest_data as $key => $val ) {
			if( !$this->objectHasProperty( $key ) ) {
				throw new Application_Modules_Exception(
					'Unknown manifest property \''.$key.'\' (Module: \''.$this->_name.'\') ',
					Application_Modules_Exception::CODE_MANIFEST_NONSENSE
				);
			}

			$this->{$key} = $val;

		}
	}


	/**
	 * Returns module root directory
	 *
	 * @return string
	 */
	public function getModuleDir()
	{
		return Application_Modules::getModuleDir( $this->_name );
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->_name;
	}

	/**
	 * @return string
	 */
	public function getNamespace()
	{
		return Application_Modules::getModuleRootNamespace().'\\'.str_replace( '.', '\\', $this->_name ).'\\';
	}

	/**
	 * @return string
	 */
	public function getVendor()
	{
		return $this->vendor;
	}

	/**
	 * @return string
	 */
	public function getVersion()
	{
		return $this->version;
	}

	/**
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Returns required API version
	 *
	 * @return int
	 */
	public function getAPIVersion()
	{
		return $this->API_version;
	}


	/**
	 * @return bool
	 */
	public function isCompatible()
	{
		return Version::getAPIIsCompatible( $this->API_version );
	}

	/**
	 * @return bool
	 */
	public function isMandatory()
	{
		return $this->is_mandatory;
	}


	/**
	 *
	 * @return array
	 */
	public function getPagesRaw()
	{
		return $this->pages;
	}

	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale $locale
	 * @param null|string|bool $translator_namespace
	 *
	 * @return Mvc_Page[]
	 */
	public function getPages( Mvc_Site_Interface $site, Locale $locale, $translator_namespace=null )
	{

		if($translator_namespace===null) {
			$translator_namespace = $this->getName();
		}

		if(
			!isset($this->pages[$site->getId()]) ||
			!is_array($this->pages[$site->getId()])
		) {
			return [];
		}

		$pages = [];

		$translate_fields = [
			'name',
			'title',
			'menu_title',
			'breadcrumb_title',
		];

		foreach( $this->pages[$site->getId()] as $page_id=>$page_data ) {
			$page_data['id'] = $page_id;

			if(isset($page_data['contents'])) {
				foreach( $page_data['contents'] as $i=>$content ) {
					if( !isset($content['module_name']) ) {
						$page_data['contents'][$i]['module_name'] = $this->getName();
					}
				}
			}

			if( $translator_namespace!==false ) {
				foreach( $translate_fields as $tf ) {
					if(!empty($page_data[$tf])) {
						$page_data[$tf] = Tr::_( $page_data[$tf], [], $translator_namespace, $locale );
					}
				}
			}

			$page = Mvc_Page::createByData( $site, $locale, $page_data );

			$pages[] = $page;

		}

		return $pages;
	}

	/**
	 * @return array
	 */
	public function getMenuItemsRaw()
	{
		return $this->menu_items;
	}

	/**
	 *
	 * @param null|string|bool $translator_namespace
	 *
	 * @return Navigation_Menu_Item[]
	 */
	public function getMenuItems( $translator_namespace=null )
	{
		if($translator_namespace===null) {
			$translator_namespace = $this->getName();
		}

		$res = [];

		foreach( $this->menu_items as $menu_id=>$menu_items_data ) {
			foreach( $menu_items_data as $item_id=>$menu_item_data ) {
				$label = '';

				if(!empty($menu_item_data['label'])) {
					if($translator_namespace!==false) {
						$label = Tr::_($menu_item_data['label'], [], $translator_namespace);
					} else {
						$label = $menu_item_data['label'];
					}
				}

				$menu_item = new Navigation_Menu_Item( $item_id, $label );
				$menu_item->setMenuId( $menu_id );
				$menu_item->setData( $menu_item_data );

				$res[] = $menu_item;
			}
		}

		return $res;
	}


	/**
	 * @return bool
	 */
	public function isInstalled()
	{
		return Application_Modules::moduleIsInstalled( $this->_name );
	}

	/**
	 * @return bool
	 */
	public function isActivated()
	{
		return Application_Modules::moduleIsActivated( $this->_name );
	}

}