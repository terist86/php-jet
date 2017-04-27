<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 * @JetDataModel:name = 'role_users'
 *
 * @JetDataModel:database_table_name = 'Jet_Auth_Users_Roles'
 *
 * @JetDataModel:M_model_class_name = 'Auth_Role'
 * @JetDataModel:N_model_class_name = 'Auth_User'
 */
class Auth_Role_Users extends DataModel_Related_MtoN {

	/**
	 * @JetDataModel:related_to = 'user.id'
	 */
	protected $user_id = '';

	/**
	 * @JetDataModel:related_to = 'role.id'
	 */
	protected $role_id = '';


}