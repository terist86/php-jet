<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\SysConf_Path;

$project_base = dirname(dirname(dirname(dirname(__DIR__)))).'/';
$studio_base = dirname(dirname(__DIR__)).'/';
$studio_application =  $studio_base.'application/';
$project_application =  $project_base.'application/';

$library = $project_base.'library/';

require_once $library.'Jet/SysConf/Path.php';
require_once $studio_base.'application/Classes/ProjectConf/Path.php';

SysConf_Path::setLibrary( $library );

SysConf_Path::setBase( $studio_base );
SysConf_Path::setPublic( $studio_base . 'public/' );
SysConf_Path::setLogs( $studio_base . 'logs/' );
SysConf_Path::setTmp( $studio_base . 'tmp/' );
SysConf_Path::setApplication( $studio_application );
SysConf_Path::setConfig( $studio_application . 'config/' );

SysConf_Path::setData( $studio_application . 'data/' );
SysConf_Path::setDictionaries( $studio_application . 'dictionaries/' );




SysConf_Path::setSites( $project_base . 'sites/' );
SysConf_Path::setCache( $project_base . 'cache/' );

ProjectConf_Path::setBase( $project_base );
SysConf_Path::setMenus( $project_application.'menus/' );

ProjectConf_Path::setApplication( $project_application );
ProjectConf_Path::setApplicationClasses( $project_application.'Classes/' );
ProjectConf_Path::setApplicationModules( $project_application.'Modules/' );

ProjectConf_Path::setSites( $project_base . 'sites/' );
ProjectConf_Path::setConfig( $project_application . 'config/' );


ProjectConf_Path::setPublic( $project_application . 'public/' );
ProjectConf_Path::setLogs( $project_application . 'logs/' );
ProjectConf_Path::setTmp( $project_application . 'tmp/' );
ProjectConf_Path::setCache( $project_application . 'cache/' );


ProjectConf_Path::setData( $project_application . 'data/' );
ProjectConf_Path::setDictionaries( $project_application . 'dictionaries/' );

ProjectConf_Path::setTemplates( $studio_base . 'templates/' );