<?php
return [
    'name' => 'Homepage',
    'title' => 'Hlavní stránka',
    'menu_title' => 'Hlavní stránka',
    'breadcrumb_title' => 'Hlavní stránka',
    'layout_script_name' => 'default',
    'meta_tags' => [
        [
            'attribute'   => 'Meta1attribute',
            'attribute_value' => 'Meta 1 attribute value',
            'content' => 'Meta 1 content'
        ],
        [
            'attribute'   => 'Meta2attribute',
            'attribute_value' => 'Meta 2 attribute value',
            'content' => 'Meta 2 content'
        ],
        [
            'attribute'   => 'Meta3attribute',
            'attribute_value' => 'Meta 3 attribute value',
            'content' => 'Meta 3 content'
        ],
    ],
    'contents' => [
	    [
		    'module_name' => 'JetExample.TestModule',
		    'controller_action' => 'test_mvc_info',
		    'output_position' => 'right',
		    'output_position_order' => 1
	    ],
	    [
		    'output_position_order' => 1,
	        'output' => ['JetApplication\PageStaticContent', 'get']
	    ],
	    [
		    'output_position_order' => 2,
		    'output' => '<hr/>&copy; Miroslav Marek'
	    ],


    ]
];
