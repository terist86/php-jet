<?php
namespace Jet;

return [
    'ID' => 'admin/ria/rest_api',
    'service_type' => Mvc::SERVICE_TYPE_REST,
    'name' => 'Admin - REST API',
	'title' => ' ',
	'menu_title' => ' ',
	'breadcrumb_title' => ' ',
	'meta_tags' => array(),
	'contents' => array(
					array(
                        'module_name' => 'JetExample\AdminUI',
                        'parser_URL_method_name' => 'parseRequestURL',
						'controller_action' => 'default',
					)
			)
];