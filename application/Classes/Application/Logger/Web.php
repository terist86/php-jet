<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Application_Logger_Interface;
use Jet\Auth_User_Interface;
use Jet\BaseObject;

/**
 *
 */
class Application_Logger_Web extends BaseObject implements Application_Logger_Interface
{

	/**
	 *
	 * @param string              $event_class
	 * @param string              $event
	 * @param string              $event_message
	 * @param string              $context_object_id (optional)
	 * @param string              $context_object_name (optional)
	 * @param mixed               $context_object_data (optional)
	 * @param Auth_User_Interface|null $current_user (optional; default: null)
	 */
	public function log( $event_class, $event, $event_message, $context_object_id = '', $context_object_name = '', $context_object_data = [], $current_user = null )
	{
		Application_Logger_Web_Event::log(
			$event_class,
			$event,
			$event_message,
			$context_object_id,
			$context_object_name,
			$context_object_data,
			$current_user
		);

	}
}