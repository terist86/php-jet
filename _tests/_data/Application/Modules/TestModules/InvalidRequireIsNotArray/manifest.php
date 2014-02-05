<?php
/**
 * @see Jet\Application_Modules_Module_Info
 */
return array(
	'API_version' => 201208,

	'label' => 'Test Module',

	'types' => array( Jet\Application_Modules_Module_Manifest::MODULE_TYPE_GENERAL ),
	'description' => 'Unit test module',

	'require' => 'IsNotArray',

	'factory_overload_map' => array(
		'OldClass1' => 'MyClass1',
		'OldClass2' => 'MyClass2',
		'OldClass3' => 'MyClass3',
	),

	'signals_callbacks' => array(
		'/test/signal1' => 'CallbackMoeduleMethodName1',
		'/test/signal2' => 'CallbackMoeduleMethodName2',
	)

);