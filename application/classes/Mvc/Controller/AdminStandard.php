<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Mvc_Controller_Standard;
use JetApplication\Application_Module_Manifest as App_Application_Module_Manifest;
use Jet\Form;
use Jet\UI_messages;

/**
 *
 */
abstract class Mvc_Controller_AdminStandard extends Mvc_Controller_Standard
{

	/**
	 * @var array
	 */
	protected static $action_URI = [];
	/**
	 * @var array
	 */
	protected static $action_regexp = [];
	/**
	 *
	 * @var App_Application_Module_Manifest
	 */
	protected $module_manifest;

	/**
	 * @param string $action
	 *
	 * @return string
	 */
	public static function getActionURI( $action )
	{
		return static::$action_URI[$action];
	}

	/**
	 * @param string $action
	 *
	 * @return string
	 */
	public static function getActionRegexp( $action )
	{
		return static::$action_regexp[$action];
	}

	/**
	 * @return App_Application_Module_Manifest
	 */
	public function getModuleManifest()
	{
		return $this->module_manifest;
	}

}