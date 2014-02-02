<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Auth
 * @subpackage Auth_User
 */
namespace Jet;

/**
 * Class Auth_User_Roles
 *
 * @JetDataModel:name = 'User_Roles'
 *
 * @JetDataModel:database_table_name = 'Jet_Auth_Users_Roles'
 *
 * @JetDataModel:M_model_class_name = 'Jet\\Auth_User_Default'
 * @JetDataModel:N_model_class_name = 'Jet\\Auth_Role_Default'
 */
class Auth_User_Roles extends DataModel_Related_MtoN {
	/**
	 * @JetDataModel:type = Jet\DataModel::TYPE_ID
	 * @JetDataModel:is_ID = true
	 */
	protected $ID;

	/**
	 * @JetDataModel:related_to = 'User.ID'
	 */
	protected $user_ID = '';

	/**
	 * @JetDataModel:related_to = 'Role.ID'
	 */
	protected $role_ID = '';

}