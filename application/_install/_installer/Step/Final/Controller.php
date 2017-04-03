<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Installer
 */
namespace JetExampleApp;

use Jet\IO_File;
use Jet\IO_File_Exception;
use Jet\Tr;

class Installer_Step_Final_Controller extends Installer_Step_Controller {


	public function main() {

		$cp_conf_source = Installer::getTmpConfigFilePath();
		$cp_conf_target = JET_CONFIG_PATH.'_common/'.JET_APPLICATION_CONFIGURATION_NAME.'.php';

		$copy_OK = true;
		$copy_message = '';
		try {
			IO_File::copy($cp_conf_source, $cp_conf_target);
		} catch(IO_File_Exception $e) {
			$copy_OK = false;
			$copy_message = $e->getMessage();
		}

		if($copy_OK) {
			$this->render('default');
		} else {
			$this->view->setVar('message', $copy_message);
			$this->view->setVar('source', $cp_conf_source);
			$this->view->setVar('target', $cp_conf_target);

			$this->render('default-copy-config');
		}


		if(JET_DEVEL_MODE) {
			$this->render('debug-warning');
		} else {
			$this->render('non-debug-warning');

		}


	}

	public function getLabel() {
		return Tr::_('Done', [], 'Final');
	}
}
