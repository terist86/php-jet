<?php
return array (
	"name" => "Admin - users",
	"title" => "Administration Interface (classic) - Users",
	"breadcrumb_title" => "Users",
	"menu_title" => "Users management",
	"URL_fragment" => "users",
	"layout" => "default",
	"is_admin_UI" => true,
	"meta_tags" => array(),
	"contents" =>
			array(
				array(
					"module_name" => "JetExample\\AdminUsers",
					"controller_action" => "default",
					"output_position" => "",
					"output_position_required" => true,
					"output_position_order" => 1
				)
			)

);