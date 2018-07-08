<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Data_DateTime;
use Jet\IO_File;
use Jet\IO_File_Exception;
use Jet\Mvc_Site;
use Jet\UI_messages;
use Jet\Tr;


/**
 *
 */
class Installer_Step_Final_Controller extends Installer_Step_Controller
{

	/**
	 * @var string
	 */
	protected $label = 'Installation finish';



	/**
	 *
	 */
	public function main()
	{

		$OK = true;

		$install_symptom_file_path = JET_PATH_DATA.'installed.txt';



		if(
			$OK &&
			Installer_Step_CreateSite_Controller::sitesCreated()
		) {
			try {
				IO_File::write( $install_symptom_file_path, Data_DateTime::now()->toString() );
			} catch( \Exception $e ) {
				UI_messages::danger( Tr::_('Something went wrong: %error%', ['error'=>$e->getMessage()]) );
				$OK = false;
			}
		}

		if( $OK ) {
			session_reset();
			$this->render( 'done' );
		} else {
			$this->render( 'error' );
		}
	}


}
