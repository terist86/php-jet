<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\Autoloader_Loader;

/**
 *
 */
class Autoloader_ProjectClasses extends Autoloader_Loader
{

	/**
	 *
	 * @param string $root_namespace
	 * @param string $namespace
	 * @param string $class_name
	 *
	 * @return bool|string
	 */
	public function getScriptPath( $root_namespace, $namespace, $class_name )
	{
		if( $root_namespace!=Project::getApplicationNamespace() ) {
			return false;
		}

		$class_name = str_replace( '_', DIRECTORY_SEPARATOR, $class_name );

		return ProjectConf_PATH::APPLICATION_CLASSES().$class_name.'.php';
	}
}