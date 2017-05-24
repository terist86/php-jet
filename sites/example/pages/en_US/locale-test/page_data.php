<?php
return [
	'id' => 'test-locale',
	'order' => 1,
    'name' => 'Locale test',
    'title' => 'Locale test',
    'layout_script_name' => 'default',
    'meta_tags' => [
    ],
    'contents' => [
	    [
		    'module_name' => 'JetExample.TestModule',
		    'controller_action' => 'test_mvc_info',
		    'output_position' => 'right',
		    'output_position_order' => 1
	    ],

	    [
		    'module_name' => 'JetExample.TestModule2',
		    'controller_action' => 'test_locale',
		    'output_position' => '',
		    'output_position_order' => 1
	    ]
    ]
];

