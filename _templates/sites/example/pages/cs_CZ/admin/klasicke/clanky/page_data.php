<?php
namespace Jet;

return [
    'ID' => 'admin/classic/articles',
    'name' => 'Administrace - články',
	'title' => 'Administrační rozhraní (klasické) - Články',
	'breadcrumb_title' => 'Články',
	'menu_title' => 'Články',
	'meta_tags' => [],
	'contents' =>
			[
				[
                    'is_dynamic' => true,
					'module_name' => 'JetExample.Articles',
                    'URL_parser_method_name' => 'parseRequestURL_Admin',
					'controller_action' => 'default',
					'output_position' => '',
					'output_position_required' => true,
					'output_position_order' => 1
				]
			]
];