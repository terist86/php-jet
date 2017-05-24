<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Admin\Administrators\Roles;

use Jet\Application_Module;

/**
 *
 */
class Main extends Application_Module
{
	const ADMIN_MAIN_PAGE = 'admin/administrators-roles';

	const ACTION_GET_ROLE = 'get_role';
	const ACTION_ADD_ROLE = 'add_role';
	const ACTION_UPDATE_ROLE = 'update_role';
	const ACTION_DELETE_ROLE = 'delete_role';

	/**
	 * @var array
	 */
	protected $ACL_actions = [
		self::ACTION_GET_ROLE    => 'Get role(s) data', self::ACTION_ADD_ROLE => 'Add new role',
		self::ACTION_UPDATE_ROLE => 'Update role', self::ACTION_DELETE_ROLE => 'Delete role',
	];


	/**
	 * @var Controller_Main_Router
	 */
	protected $admin_controller_router;

	/**
	 * @return Controller_Main_Router
	 */
	public function getAdminControllerRouter()
	{

		if( !$this->admin_controller_router ) {
			$this->admin_controller_router = new Controller_Main_Router( $this );
		}

		return $this->admin_controller_router;
	}
}