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
class SysConf_Jet_Modules
{
	protected static string $install_directory = '_install';
	protected static string $install_script = 'install.php';
	protected static string $uninstall_script = 'uninstall.php';
	protected static string $views_dir = 'views';
	protected static string $module_menu_items_dir = 'menuItems';
	protected static string $pages_dir = 'pages';
	protected static string $module_root_namespace = 'JetApplicationModule';
	protected static string $manifest_file_name = 'manifest.php';

	/**
	 * @return string
	 */
	public static function getInstallDirectory(): string
	{
		return static::$install_directory;
	}

	/**
	 * @param string $install_directory
	 */
	public static function setInstallDirectory( string $install_directory ): void
	{
		static::$install_directory = $install_directory;
	}

	/**
	 * @return string
	 */
	public static function getInstallScript(): string
	{
		return static::$install_script;
	}

	/**
	 * @param string $install_script
	 */
	public static function setInstallScript( string $install_script ): void
	{
		static::$install_script = $install_script;
	}

	/**
	 * @return string
	 */
	public static function getUninstallScript(): string
	{
		return static::$uninstall_script;
	}

	/**
	 * @param string $uninstall_script
	 */
	public static function setUninstallScript( string $uninstall_script ): void
	{
		static::$uninstall_script = $uninstall_script;
	}

	/**
	 * @return string
	 */
	public static function getViewsDir(): string
	{
		return static::$views_dir;
	}

	/**
	 * @param string $views_dir
	 */
	public static function setViewsDir( string $views_dir ): void
	{
		static::$views_dir = $views_dir;
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
	 * @return string
	 */
	public static function getModuleRootNamespace(): string
	{
		return static::$module_root_namespace;
	}

	/**
	 * @param string $module_root_namespace
	 */
	public static function setModuleRootNamespace( string $module_root_namespace ): void
	{
		static::$module_root_namespace = $module_root_namespace;
	}

	/**
	 * @return string
	 */
	public static function getManifestFileName(): string
	{
		return static::$manifest_file_name;
	}

	/**
	 * @param string $manifest_file_name
	 */
	public static function setManifestFileName( string $manifest_file_name ): void
	{
		static::$manifest_file_name = $manifest_file_name;
	}

	/**
	 * @return string
	 */
	public static function getPagesDir(): string
	{
		return static::$pages_dir;
	}

	/**
	 * @param string $pages_dir
	 */
	public static function setPagesDir( string $pages_dir ): void
	{
		static::$pages_dir = $pages_dir;
	}



}