<?php
namespace JetStudio;

use Jet\DataModel_Backend;
use Jet\Http_Headers;
use Jet\IO_File;
use Jet\UI_messages;
use Jet\Tr;

$current = DataModels::getCurrentModel();
if(!$current) {
	die();
}

header('Content-Type: text/plain');


$updated = false;
$ok = true;
try {
	$class = $current->createClass();

	if(!$class) {
		UI_messages::danger( Tr::_('DataModel is not ready! Please check definition errors.') );

		Http_Headers::reload([],['action']);
	}


	$path = $current->getClassPath();

	$updated = !IO_File::exists( $path );

	$class->write( $path );

} catch( \Exception $e ) {

	$ok = false;

	Application::handleError( $e );
}

if($ok) {
	if( $updated ) {
		UI_messages::success( Tr::_( 'Class <b>%class%</b> has been created', ['class'=>$current->getClassName()] ) );
	} else {
		UI_messages::success( Tr::_( 'Class <b>%class%</b> has been updated', ['class'=>$current->getClassName()] ) );
	}
}

Http_Headers::reload([],['action']);