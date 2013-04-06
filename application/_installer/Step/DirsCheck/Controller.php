<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Installer
 */
namespace Jet;

class Installer_Step_DirsCheck_Controller extends Installer_Step_Controller {

	public function main() {
		if(Http_Request::POST()->exists("go")) {
			$this->installer->goNext();
		}

		$dirs = array(
			JET_DATA_PATH => array(
				"is_required" => true,
				"is_writeable" => false
			),
			JET_TMP_PATH => array(
				"is_required" => true,
				"is_writeable" => false
			),
			JET_LOGS_PATH => array(
				"is_required" => true,
				"is_writeable" => false
			),
			JET_APPLICATION_SITES_PATH => array(
				"is_required" => true,
				"is_writeable" => false
			),
			JET_APPLICATION_CONFIG_PATH => array(
				"is_required" => false,
				"is_writeable" => false,
				"comment" => "Never mind. In fact, it is better that the directory is not writeable. But you have to complete the installation manually."
			)
		);


		$is_OK = true;
		foreach( $dirs as $dir=>$dir_data ) {
			$dirs[$dir]["is_writeable"] = IO_Dir::isWritable( $dir );
			if(!$dirs[$dir]["is_writeable"] && $dir_data["is_required"]) {
				$is_OK = false;
			}
		}

		$this->view->setVar("is_OK", $is_OK);
		$this->view->setVar("dirs", $dirs);



		$this->render("default");
	}

	public function getLabel() {
		return Tr::_("Check directories permissions", array(), "DirsCheck");
	}

}
