<?php
use Jet\SysConf_Path;
use Jet\SysConf_URI;

require_once SysConf_Path::LIBRARY().'Jet/SysConf/URI.php';



if(isset( $_SERVER['REQUEST_URI'] )) {
	$base_URI = $_SERVER['REQUEST_URI'];
	if(
		strpos($base_URI, '.') ||
		strpos($base_URI, '?')
	) {
		$base_URI = dirname($base_URI).'/';
	}
} else {
	$base_URI = '/_tools/studio/';
}

SysConf_URI::setBase($base_URI);
SysConf_URI::setPublic($base_URI.'public/');
