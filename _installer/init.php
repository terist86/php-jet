<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication\Installer;

use Jet\Config;
use Jet\SysConf_PATH;

Config::setBeTolerant( true );


require 'Classes/Installer.php';


Installer::setApplicationNamespace('JetApplication');
Installer::setBasePath( SysConf_PATH::BASE().'_installer/' );

Installer::setSteps(
	[
		'Welcome',
		'SystemCheck',
		'DirsCheck',
		'SelectDbType',
		'CreateDB',
		'SelectLocales',
		'CreateSite',
		'Mailing',
		'InstallModules',
		'CreateAdministrator',
		'ConfigureStudio',
		'Final',
	]
);

Installer::setAvailableLocales(
	[
		'en_US', 'cs_CZ',
	]
);
