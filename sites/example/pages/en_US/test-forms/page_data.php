<?php
return [
	'id' => 'forms-test',
	'order' => 100,
    'name' => 'Test - Forms',
    'title' => 'Test - Forms',
    'layout_script_name' => 'default',
    'meta_tags' => [
    ],
    'contents' => [
	    [
		    'module_name' => 'JetExample.Test.Forms',
		    'controller_action' => 'test_forms',
		    'output_position_order' => 1
	    ],
	    [
		    'module_name' => 'JetExample.Test.Forms',
		    'controller_action' => 'test_forms_data_model',
		    'output_position_order' => 2
	    ],

    ]
];

