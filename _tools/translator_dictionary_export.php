<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package tools
 */
namespace Jet;
require "includes/bootstrap_cli.php";

function show_usage() {
	global $argv;

	echo "Usage: ".PHP_EOL;
	echo "\t\tphp {$argv[0]}Namespace loclae [installer]".PHP_EOL;
	echo PHP_EOL;
	echo "example: php {$argv[0]} ModuleName en_US".PHP_EOL;
	echo "\t\texports module 'ModuleName' en_US dictionary ".PHP_EOL.PHP_EOL;

	echo "example: php {$argv[0]} ".Translator::COMMON_NAMESPACE." en_US".PHP_EOL.PHP_EOL;
	echo "\t\texports common en_US dictionary ".PHP_EOL.PHP_EOL;

	echo "example: php {$argv[0]} StepOne en_US installer".PHP_EOL;
	echo "\t\texports installer step 'StepOne' en_US dictionary ".PHP_EOL.PHP_EOL;

	echo "example: php {$argv[0]} ".Translator::COMMON_NAMESPACE." en_US installer".PHP_EOL;
	echo "\t\texports installer common en_US dictionary ".PHP_EOL.PHP_EOL;
	die( );
}

if(!isset($argv[2])){
	show_usage();
}

if(isset($argv[3])) {
	if($argv[3]=="installer") {
		require JET_APPLICATION_PATH."_install/_installer/Installer.php";
		Installer::initTranslator();
	} else {
		show_usage();
	}
}
$namespace = $argv[1];
$locale = new Locale($argv[2]);


echo Translator::exportDictionary( $namespace, $locale );