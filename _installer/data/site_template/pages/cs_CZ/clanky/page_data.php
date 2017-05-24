<?php
return [
    'id' => 'articles',
	'order' => 2,
    'name' => 'Články',
	'title' => 'Články',
	'menu_title' => 'Články',
	'breadcrumb_title' => 'Články',
	'layout_script_name' => 'default',
	'meta_tags' => [
		[
			'attribute'   => 'name',
			'attribute_value' => 'description',
			'content' => 'Články'
		],
	],
	'contents' => [
			[
				'module_name' => 'JetExample.Articles',
				'controller_action' => 'default',
				'output_position' => '',
				'output_position_order' => 1
			]
	]
];