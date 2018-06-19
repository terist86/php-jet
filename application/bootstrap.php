<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\IO_File;
use Jet\Http_Request;


define( 'JET_CONFIG_ENVIRONMENT', 'development' );
//define( 'JET_CONFIG_ENVIRONMENT', 'production' );


$config_dir = __DIR__.'/config/'.JET_CONFIG_ENVIRONMENT.'/';
require( $config_dir.'jet.php' );
require( $config_dir.'paths.php' );
require( $config_dir.'URI.php' );
require( $config_dir.'js_css.php' );


$init_dir = __DIR__.'/Init/';
require( $init_dir.'Profiler.php' );
require( $init_dir.'PHP.php' );
require( $init_dir.'ErrorHandler.php' );
require( $init_dir.'Autoloader.php' );
require( $init_dir.'ClassNames.php' );


//- REMOVE AFTER INSTALLATION -------------
$installer_path = JET_PATH_BASE.'_installer/install.php';
$install_symptom_file = JET_PATH_DATA.'installed.txt';
if(
	IO_File::exists( $installer_path ) &&
	!IO_File::exists( $install_symptom_file )
) {
	/** @noinspection PhpIncludeInspection */
	require( $installer_path );
	die();
}
//- REMOVE AFTER INSTALLATION -------------

require( $init_dir.'Cache.php' );



Http_Request::initialize( JET_HIDE_HTTP_REQUEST );

Application::runMvc();

Application::end();
