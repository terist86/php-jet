<?php
return array (
	"name" => "Admin - roles",
	"title" => "Administrační rozhraní (klasické) - Role",
	"URL_fragment" => "role",
	"layout" => "default",
	"is_admin_UI" => true,
	"meta_tags" => array(),
	"contents" =>
			array(
				array(
					"module_name" => "Jet\\AdminRoles",
					"controller_action" => "default",
					"output_position" => "",
					"output_position_required" => true,
					"output_position_order" => 1
				)
			)

);