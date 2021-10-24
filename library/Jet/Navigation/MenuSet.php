<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Navigation_MenuSet extends BaseObject
{
	/**
	 * @var string|null
	 */
	protected static string|null $menus_dir_path = null;

	/**
	 * @var string
	 */
	protected static string $module_menu_items_dir = 'menuItems';

	/**
	 * @var string
	 */
	protected string $name = '';

	/**
	 * @var string
	 */
	protected string $config_file_path = '';

	/**
	 * @var string|null
	 */
	protected string|null $translator_namespace = '';

	/**
	 * @var Navigation_Menu[]
	 */
	protected array $menus = [];

	/**
	 * @var Navigation_Menu_Item[]
	 */
	protected array $all_menu_items;


	/**
	 * @var Navigation_MenuSet[]
	 */
	protected static array $_sets = [];


	/**
	 * @return string
	 */
	public static function getMenusDirPath(): string
	{
		if( !static::$menus_dir_path ) {
			static::$menus_dir_path = SysConf_Path::getMenus();
		}

		return static::$menus_dir_path;
	}

	/**
	 * @param string $menus_path
	 */
	public static function setMenusDirPath( string $menus_path ): void
	{
		static::$menus_dir_path = $menus_path;
	}

	/**
	 * @return string
	 */
	public static function getModuleMenuItemsDir(): string
	{
		return static::$module_menu_items_dir;
	}

	/**
	 * @param string $module_menu_items_dir
	 */
	public static function setModuleMenuItemsDir( string $module_menu_items_dir ): void
	{
		static::$module_menu_items_dir = $module_menu_items_dir;
	}



	/**
	 * @param string $name
	 * @param string|null|bool $translator_namespace
	 *
	 * @return Navigation_MenuSet
	 */
	public static function get( string $name, string|null|bool $translator_namespace = null ): Navigation_MenuSet
	{
		if( !isset( static::$_sets[$name] ) ) {
			static::$_sets[$name] = new static( $name, $translator_namespace );
		}

		return static::$_sets[$name];
	}

	/**
	 * @return Navigation_MenuSet[]
	 */
	public static function getList(): iterable
	{
		$files = IO_Dir::getList( static::getMenusDirPath(), '*.php', false );

		foreach( $files as $path => $name ) {
			$name = pathinfo( $name )['filename'];
			static::get( $name );
		}

		return static::$_sets;
	}


	/**
	 * @param string $name
	 * @param string|null|bool $translator_namespace
	 */
	public function __construct( string $name, string|null|bool $translator_namespace = null )
	{
		$this->setName( $name );
		$this->translator_namespace = $translator_namespace;
		$this->init();
	}

	/**
	 * @param string $name
	 */
	public function setName( string $name ): void
	{
		$this->name = $name;
		$this->config_file_path = static::getMenusDirPath() . $name . '.php';
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $text
	 * @return string
	 */
	protected function _( string $text ): string
	{
		if( $this->translator_namespace === false ) {
			return $text;
		}

		return Tr::_( $text, [], $this->translator_namespace );
	}

	/**
	 *
	 */
	protected function init(): void
	{
		$menu_data = require $this->config_file_path;

		foreach( $menu_data as $id => $item_data ) {
			if( empty( $item_data['icon'] ) ) {
				$item_data['icon'] = '';
			}

			$root_menu = $this->addMenu(
				$id,
				$this->_( $item_data['label'] ),
				$item_data['icon']
			);

			if( isset( $item_data['items'] ) ) {
				foreach( $item_data['items'] as $menu_item_id => $menu_item_data ) {
					$label = $this->_( $menu_item_data['label'] );
					$menu_item = new Navigation_Menu_Item( $menu_item_id, $label );
					$menu_item->setData( $menu_item_data );

					$root_menu->addItem( $menu_item );
				}

			}

		}

		$this->initModuleMenuItems();
	}


	/**
	 *
	 */
	protected function initModuleMenuItems(): void
	{
		foreach( Application_Modules::activatedModulesList() as $manifest ) {

			$items_file_path = $manifest->getModuleDir().static::getModuleMenuItemsDir().'/'.$this->name.'.php';
			if(!IO_File::isReadable($items_file_path)) {
				continue;
			}

			$menu_data = require $items_file_path;

			$translator_namespace = $manifest->getName();

			foreach($menu_data as $menu_id=>$menu_items_data) {
				$menu = $this->getMenu( $menu_id );
				if( !$menu ) {
					continue;
				}

				foreach( $menu_items_data as $item_id => $menu_item_data ) {
					$label = '';

					if( !empty( $menu_item_data['label'] ) ) {
						$label = Tr::_( $menu_item_data['label'], [], $translator_namespace );
					}

					$menu_item = new Navigation_Menu_Item( $item_id, $label );
					$menu_item->setMenuId( $menu_id );
					$menu_item->setData( $menu_item_data );

					$menu->addItem( $menu_item );
				}
			}
		}
	}


	/**
	 * @param string $id
	 *
	 * @param string $label
	 * @param string $icon
	 * @param int|null $index
	 *
	 * @return Navigation_Menu
	 * @throws Navigation_Menu_Exception
	 *
	 */
	public function addMenu( string $id, string $label, string $icon = '', int|null $index = null ): Navigation_Menu
	{
		if( isset( $this->menus[$id] ) ) {
			throw new Navigation_Menu_Exception( 'Menu ID conflict: ' . $id . ' Menu set:' . $this->name );
		}

		if( $index === null ) {
			$index = count( $this->menus ) + 1;
		}

		$menu = new Navigation_Menu( $id, $label, $index, $icon );

		$this->menus[$id] = $menu;

		return $menu;
	}


	/**
	 * @param string $id
	 *
	 * @return Navigation_Menu|null
	 */
	public function getMenu( string $id ): Navigation_Menu|null
	{
		if( !isset( $this->menus[$id] ) ) {
			return null;
		}

		return $this->menus[$id];
	}

	/**
	 * @return Navigation_Menu[]
	 */
	public function getMenus(): array
	{
		return $this->menus;
	}


	/**
	 *
	 */
	public function saveDataFile(): void
	{
		$res = [];

		foreach( $this->menus as $menu ) {
			$menu_id = $menu->getId();

			$res[$menu_id] = $menu->toArray();

		}

		IO_File::writeDataAsPhp( $this->config_file_path, $res );
	}

}