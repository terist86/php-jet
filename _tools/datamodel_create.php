<?php
/**
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package tools
 */
namespace Jet;

require 'includes/bootstrap_cli.php';

if(!isset($argv[1])) {
	die('Usage: '.$argv[0].' \'Namespace\ClassName\''.JET_EOL );
}


$class = $argv[1];

echo JET_EOL.'Create for class \''.$class.'\''.JET_EOL;

try {
	class_exists($class);
} catch( Autoloader_Exception $e ) {
	echo JET_EOL.'ERROR: '.$e->getMessage().JET_EOL.JET_EOL;
	die();
}

echo DataModel::helper_getCreateCommand($class);
DataModel::helper_create($class);
echo JET_EOL.JET_EOL;;