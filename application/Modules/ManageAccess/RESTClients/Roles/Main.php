<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\ManageAccess\RESTClients\Roles;

use Jet\Application_Module;

/**
 *
 */
class Main extends Application_Module
{
	const ADMIN_MAIN_PAGE = 'rest-clients-roles';

	const ACTION_GET_ROLE = 'get_role';
	const ACTION_ADD_ROLE = 'add_role';
	const ACTION_UPDATE_ROLE = 'update_role';
	const ACTION_DELETE_ROLE = 'delete_role';


}