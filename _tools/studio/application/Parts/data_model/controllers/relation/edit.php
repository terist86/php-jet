<?php
namespace JetStudio;

use Jet\Http_Request;
use Jet\Tr;
use Jet\UI_messages;
use Jet\Exception;
use Jet\AJAX;

$current = DataModels::getCurrentModel();

$relation = $current->getExternalRelation( Http_Request::GET()->getString('relation') );

/**
 * @var DataModel_Definition_Relation_External $relation
 */
if(!$relation) {
	Application::end();
}

$ok = false;
$data = [];
$snippets = [];


if( $relation->catchEditForm() ) {

	$form = $relation->getEditForm();

	if(DataModels::save($form)) {
		$ok = true;
		$form->setCommonMessage( UI_messages::createSuccess(Tr::_('Saved ...')) );
	}

}



$view = Application::getView();
$view->setVar('relation', $relation);

$snippets['relation_detail_area_'.$relation->getInternalId()] = $view->render('model_edit/relations/list/item-body');
$snippets['relation_header_area_'.$relation->getInternalId()] = $view->render('model_edit/relations/list/item-header');

AJAX::formResponse(
	$ok,
	$snippets,
	$data
);