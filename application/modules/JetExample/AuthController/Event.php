<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */

namespace JetApplicationModule\JetExample\AuthController;

use Jet\DataModel;
use Jet\Data_DateTime;
use Jet\Http_Request;

/**
 * Class Event
 *
 * @JetDataModel:name = 'Auth_Event'
 * @JetDataModel:database_table_name = 'Jet_Auth_Events'
 * @JetDataModel:ID_class_name = 'DataModel_ID_UniqueString'
 */
class Event extends DataModel {

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID
	 * @JetDataModel:is_ID = true
	 *
	 * @var string
	 */
	protected $ID = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATE_TIME
	 * @JetDataModel:form_field_is_required = true
	 *
	 * @var Data_DateTime
	 */
	protected $date_time;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_is_required = true
	 *
	 * @var string
	 */
	protected $event = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 1024
	 * @JetDataModel:form_field_is_required = true
	 *
	 * @var string
	 */
	protected $event_txt = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 134217728
	 * @JetDataModel:form_field_is_required = true
	 *
	 * @var string
	 */
	protected $event_data = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_is_required = false
	 *
	 * @var string
	 */
	protected $user_ID = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_is_required = false
	 *
	 * @var string
	 */
	protected $user_login = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 65536
	 * @JetDataModel:form_field_is_required = true
	 *
	 * @var string
	 */
	protected $request_URL = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 134217728
	 * @JetDataModel:form_field_is_required = false
	 *
	 * @var string
	 */
	protected $request_data = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 45
	 * @JetDataModel:form_field_is_required = true
	 *
	 * @var string
	 */
	protected $remote_IP = '';


	/**
	 * @return Data_DateTime
	 */
	public function getDateTime() {
		return $this->date_time;
	}

	/**
	 * @return string
	 */
	public function getEvent() {
		return $this->event;
	}

	/**
	 * @return string
	 */
	public function getEventData() {
		return $this->event_data;
	}

	/**
	 * @return string
	 */
	public function getEventTxt() {
		return $this->event_txt;
	}

	/**
	 * @return string
	 */
	public function getRemoteIP() {
		return $this->remote_IP;
	}

	/**
	 * @return string
	 */
	public function getRequestURL() {
		return $this->request_URL;
	}

	/**
	 * @return string
	 */
	public function getRequestData() {
		return $this->request_data;
	}

	/**
	 * @return string
	 */
	public function getUserID() {
		return $this->user_ID;
	}

	/**
	 * @return string
	 */
	public function getUserLogin() {
		return $this->user_login;
	}

	/**
	 * Log auth event
	 *
	 * @param string $event
	 * @param mixed $event_data
	 * @param string $event_txt
	 * @param string $user_ID
	 * @param string $user_login
	 *
	 * @return Event
	 */
	public static function logEvent( $event, $event_data, $event_txt, $user_ID, $user_login ) {
		$event_i = new self();

		$event_i->date_time = Data_DateTime::now();
		$event_i->event = $event;
		$event_i->event_data = json_encode($event_data);
		$event_i->event_txt = $event_txt;
		$event_i->user_ID = $user_ID;
		$event_i->user_login = $user_login;

		$event_i->request_URL = Http_Request::getURL();
		$event_i->remote_IP = Http_Request::getClientIP();

		$request_data = Http_Request::getRawPostData();

		$event_i->request_data = json_encode($request_data);

		$event_i->save();

		return $event_i;
	}
}