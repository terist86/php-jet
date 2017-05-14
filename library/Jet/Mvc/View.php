<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Mvc_View extends Mvc_View_Abstract
{

	/**
	 *
	 * @param string $scripts_dir
	 */
	public function __construct( $scripts_dir )
	{
		$this->setScriptsDir( $scripts_dir );


		$this->_data = new Data_Array();
	}


	/**
	 *
	 * @param string $script_name
	 *
	 * @throws Mvc_View_Exception
	 *
	 * @return string
	 */
	public function render( $script_name )
	{
		$this->setScriptName( $script_name );
		$this->getScriptPath();

		ob_start();

		if( static::getAddScriptPathInfoEnabled() ) {
			echo '<!-- VIEW START: '.$this->_script_path.' -->';
		}


		/** @noinspection PhpIncludeInspection */
		include $this->_script_path;

		if( static::getAddScriptPathInfoEnabled() ) {
			echo '<!-- VIEW END: '.$this->_script_path.' -->';
		}

		$result = ob_get_clean();

		$this->handlePostprocessors( $result );

		return $result;
	}

}