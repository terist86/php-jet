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
namespace JetApplicationModule\JetExample\Articles;
use Jet;
use Jet\Mvc_Controller_AJAX;

class Controller_Admin_AJAX extends Mvc_Controller_AJAX {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;


	protected static $ACL_actions_check_map = [
		'default' => false
	];

	/**
	 *
	 */
	public function initialize() {
	}


	function default_Action() {
		$article = new Article();
		$form = $article->getCommonForm();
		$form->enableDecorator('Dojo');

		$this->view->setVar('form', $form);

		$this->render('ria/default');
	}

}