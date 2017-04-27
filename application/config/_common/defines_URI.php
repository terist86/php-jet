<?php

//- It is better to hardcode constant on the production system
$base_URI = null;

$request_URI = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
list($request_URI) = explode('?', $request_URI);

$base_URI = '/';
$URI_path_parts = explode( '/', ltrim( $request_URI, '/' ) );
$got_base_URI = false;

while($URI_path_parts){

	$bootstrap_path = $_SERVER['DOCUMENT_ROOT'] . $base_URI . 'application/bootstrap.php';
	if( file_exists($bootstrap_path) ){
		$got_base_URI = true;
		break;
	}
	$base_URI .= array_shift($URI_path_parts) . '/';
}

if(!$got_base_URI){
	trigger_error('Unable to determine base URI...', E_USER_ERROR);
}
//----------------------------------------------------------------

define('JET_BASE_URI', $base_URI);

define('JET_PUBLIC_URI', JET_BASE_URI . 'public/');