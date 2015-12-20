<?php
/**
 * @see Jet\Application_Modules_Module_Info
 */
return [
	'API_version' => 201401,

	'label' => 'Test Module',

	'types' => [Jet\Application_Modules_Module_Manifest::MODULE_TYPE_GENERAL],
	'description' => 'Unit test module',

	'require' => [
			'RequireModule1',
			'RequireModule2'
	],

	'factory_overload_map' => [
		'OldClass1' => 'MyNs\MyClass1',
		'OldClass2' => 'MyNs\MyClass2',
		'OldClass3' => 'MyNs\MyClass3',
	],

	'signals_callbacks' => 'IsNotArray'

];