<?php
namespace Jet;

return [
    'ID' => 'articles',
    'name' => 'Articles',
	'title' => 'Články',
	'menu_title' => 'Články',
	'breadcrumb_title' => 'Články',
	'layout_script_name' => 'default',
	'headers_suffix' => '',
	'body_prefix' => '',
	'body_suffix' => '',
	'meta_tags' => array(
		array(
			'attribute'   => 'name',
			'attribute_value' => 'description',
			'content' => 'Articles'
		),
	),
	'contents' => array(
			array(
				'module_name' => 'JetExample\Articles',
                'parser_URL_method_name' => 'parseRequestURL_Public',
				'controller_action' => 'default',
				'output_position' => '',
				'output_position_required' => true,
				'output_position_order' => 1
			)
		)
];