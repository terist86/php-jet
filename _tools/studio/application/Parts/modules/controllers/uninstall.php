<?php
namespace JetStudio;

use Jet\Http_Headers;
use Jet\Tr;
use Jet\UI_messages;

$current = Modules::getCurrentModule();

if(!$current) {
	die();
}

if($current->getIsInstalled()) {
	$current->setIsActive(false);
	$current->setIsInstalled(false);


	if(Modules::save()) {
		if(Modules::setInstalledAndActivatedList()) {
			UI_messages::info( Tr::_('Module <b>%module%</b> has been uninstalled', [
				'module' => $current->getName()
			]) );
		}
	}

}


Http_Headers::reload([], ['action']);