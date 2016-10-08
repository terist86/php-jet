<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\AdminUsers;

use Jet\Application_Modules_Module_Abstract;

class Main extends Application_Modules_Module_Abstract {
	protected $ACL_actions = [
		'get_user' => 'Get user(s) data',
		'add_user' => 'Add new user',
		'update_user' => 'Update user',
		'delete_user' => 'Delete user',
	];


}