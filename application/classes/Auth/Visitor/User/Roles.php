<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Auth_User_Roles;

/**
 *
 *
 * @JetDataModel:database_table_name = 'users_visitors_roles'
 *
 * @JetDataModel:M_model_class_name = 'Auth_Visitor_User'
 * @JetDataModel:N_model_class_name = 'Auth_Visitor_Role'
 */
class Auth_Visitor_User_Roles extends Auth_User_Roles
{
}