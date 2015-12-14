<?php
namespace Jet;

return [
    'ID' => 'subpage_3_2',
    'name' => 'Podstránka 3-2',
	'title' => 'Podstránka 3-2',
	'menu_title' => 'Podstránka 3-2',
	'breadcrumb_title' => 'Podstránka 3-2',
	'layout_script_name' => 'default',
	'meta_tags' => array(
			array(
				'attribute'   => 'Meta1attribute',
				'attribute_value' => 'Meta 1 attribute value',
				'content' => 'Meta 1 content'
			),
			array(
				'attribute'   => 'Meta2attribute',
				'attribute_value' => 'Meta 2 attribute value',
				'content' => 'Meta 2 content'
			),
			array(
				'attribute'   => 'Meta3attribute',
				'attribute_value' => 'Meta 3 attribute value',
				'content' => 'Meta 3 content'
			),
	 ),
    'contents' => array(
        array(
            'is_dynamic' => true,
            'module_name' => 'JetExample.TestModule',
            'controller_action' => 'test_action2',
            'output_position' => '',
            'output_position_required' => true,
            'output_position_order' => 1
        ),
        array(
            'module_name' => 'JetExample.TestModule2',
            'controller_action' => 'test_action1',
            'output_position' => 'right',
            'output_position_required' => true,
            'output_position_order' => 1
        ),
        array(
            'is_dynamic' => true,
            'module_name' => 'JetExample.TestModule2',
            'controller_action' => 'test_action2',
            'output_position' => 'right',
            'output_position_required' => true,
            'output_position_order' => 1
        ),
    )
];