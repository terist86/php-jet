<?php
namespace JetStudio;

use Jet\Http_Request;
use Jet\Tr;
use Jet\UI_messages;
use Jet\Exception;
use Jet\AJAX;

$current = DataModels::getCurrentModel();

$key = $current->getKey( Http_Request::GET()->getString('key') );

/**
 * @var DataModels_Key $key
 */
if(!$key) {
	Application::end();
}

$ok = false;
$data = [];
$snippets = [];


if( $key->catchEditForm() ) {

	$form = $key->getEditForm();

	if(DataModels::save($form)) {
		$ok = true;

		$form->setCommonMessage( UI_messages::createSuccess(Tr::_('Saved ...')) );
	}

}



$view = Application::getView();
$view->setVar('key', $key);

$snippets['key_detail_area_'.$key->getInternalId()] = $view->render('data_model/model_edit/keys/list/item-body');
$snippets['key_header_area_'.$key->getInternalId()] = $view->render('data_model/model_edit/keys/list/item-header');

AJAX::formResponse(
	$ok,
	$snippets,
	$data
);