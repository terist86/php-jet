<?php
return array (
	"name" => "Admin - roles",
	"title" => "Administration Interface (classic) - Roles",
	"breadcrumb_title" => "Roles",
	"menu_title" => "Roles management",
	"URL_fragment" => "roles",
	"layout" => "default",
	"is_admin_UI" => true,
	"meta_tags" => array(),
	"contents" =>
			array(
				array(
					"module_name" => "JetExample\\AdminRoles",
					"controller_action" => "default",
					"output_position" => "",
					"output_position_required" => true,
					"output_position_order" => 1
				)
			)

);