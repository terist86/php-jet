<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

$module_name = require "init/init_modules.php";

echo "Installing module '{$module_name}' ... " . PHP_EOL;

try {
	Application_Modules::installModule($module_name);
} catch (Exception $e) {
	handleException($e);
}


echo "Activating module '{$module_name}' ... " . PHP_EOL;

try {
	Application_Modules::activateModule($module_name);
} catch (Exception $e) {
	handleException($e, 101);
}

ok();