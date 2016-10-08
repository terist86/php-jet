<?php
/**
 *
 *
 *
 * Default admin UI module
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\AdminUsers;

use JetApplicationModule\JetExample\UIElements;
use Jet\Application_Modules;
use Jet\Mvc;
use Jet\Mvc_Controller_Standard;
use Jet\Mvc_Controller_Router;
use Jet\Mvc_Page_Content_Interface;
use Jet\Auth_User;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Tr;

class Controller_Main extends Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

    /**
     * @var Mvc_Controller_Router
     */
    protected $micro_router;


	protected static $ACL_actions_check_map = [
		'default' => 'get_user',
		'add' => 'add_user',
		'edit' => 'update_user',
		'view' => 'get_user',
		'delete' => 'delete_user',
	];


	/**
	 *
	 */
	public function initialize() {
		Mvc::checkCurrentContentIsDynamic();
        Mvc::getCurrentPage()->breadcrumbNavigationShift( -2 );
		$this->getMicroRouter();
		$this->view->setVar( 'router', $this->micro_router );
	}

    /**
     * @param Mvc_Page_Content_Interface $page_content
     *
     * @return bool
     */
    public function parseRequestURL( Mvc_Page_Content_Interface $page_content=null ) {
        $router = $this->getMicroRouter();

        return $router->resolve( $page_content );
    }

    /**
     *
     * @return Mvc_Controller_Router
     */
    public function getMicroRouter() {
        if($this->micro_router) {
            return $this->micro_router;
        }

        $router = Mvc::getCurrentRouter();

        $router = new Mvc_Controller_Router( $router, $this->module_instance );

        $base_URI = Mvc::getCurrentPageURI();

        $validator = function( &$parameters ) {

            $user = Auth_User::get( $parameters[0] );
            if(!$user) {
                return false;
            }

            $parameters['user'] = $user;
            return true;

        };

        $router->addAction('add', '/^add$/', 'add_user', true)
            ->setCreateURICallback( function() use($base_URI) { return $base_URI.'add/'; } );

        $router->addAction('edit', '/^edit:([\S]+)$/', 'update_user', true)
            ->setCreateURICallback( function( Auth_User $user ) use($base_URI) { return $base_URI.'edit:'.rawurlencode($user->getID()).'/'; } )
            ->setParametersValidatorCallback( $validator );

        $router->addAction('view', '/^view:([\S]+)$/', 'get_user', true)
            ->setCreateURICallback( function( Auth_User $user ) use($base_URI) { return $base_URI.'view:'.rawurlencode($user->getID()).'/'; } )
            ->setParametersValidatorCallback( $validator );

        $router->addAction('delete', '/^delete:([\S]+)$/', 'delete_user', true)
            ->setCreateURICallback( function( Auth_User $user ) use($base_URI) { return $base_URI.'delete:'.rawurlencode($user->getID()).'/'; } )
            ->setParametersValidatorCallback( $validator );

        $this->micro_router = $router;

        return $router;
    }


	/**
	 *
	 */
	public function default_Action() {

		/**
		 * @var UIElements\Main $UI_m
		 */
		$UI_m = Application_Modules::getModuleInstance('JetExample.UIElements');
		$grid = $UI_m->getDataGridInstance();

		$grid->setIsPersistent('admin_classic_users_list_grid');

		$grid->addColumn('_edit_', '')->setAllowSort(false);
		$grid->addColumn('login', Tr::_('Login') );
		$grid->addColumn('ID', Tr::_('ID') );

		$grid->setData( Auth_User::getList() );

		$this->view->setVar('grid', $grid);

		$this->render('classic/default');

	}

	/**
	 *
	 */
	public function add_Action() {

		/**
		 * @var Auth_User $user
		 */
		$user = new Auth_User();


		$form = $user->getCommonForm();

		if( $user->catchForm( $form ) ) {
			$user->save();
			Http_Headers::movedTemporary( $this->micro_router->getActionURI( 'edit', $user ) );
		}

		$this->view->setVar('bnt_label', 'ADD');

        Mvc::getCurrentPage()->addBreadcrumbNavigationData('New user');

		$this->view->setVar('has_access', true);
		$this->view->setVar('form', $form);

		$this->render('classic/edit');

	}

	/**
     *
	 */
	public function edit_Action() {

        /**
         * @Auth_User $user
         */
        $user = $this->getActionParameterValue('user');

		$form = $user->getCommonForm();

		if( $user->catchForm( $form ) ) {
			$user->save();
			Http_Headers::movedTemporary( $this->micro_router->getActionURI( 'edit', $user ) );
		}


		$this->view->setVar('bnt_label', 'SAVE' );

        Mvc::getCurrentPage()->addBreadcrumbNavigationData( $user->getLogin() );


		$this->view->setVar('has_access', true);
		$this->view->setVar('form', $form);

		$this->render('classic/edit');

	}

	/**
     *
	 */
	public function view_Action() {

        /**
         * @Auth_User $user
         */
        $user = $this->getActionParameterValue('user');

		$form = $user->getCommonForm();

		$this->view->setVar('bnt_label', 'SAVE' );

        Mvc::getCurrentPage()->addBreadcrumbNavigationData( $user->getLogin() );


		$this->view->setVar('has_access', false);
		$this->view->setVar('form', $form);

		$this->render('classic/edit');

	}



	/**
     *
	 */
	public function delete_Action() {

        /**
         * @Auth_User $user
         */
        $user = $this->getActionParameterValue('user');

		if( Http_Request::POST()->getString('delete')=='yes' ) {
			$user->delete();
			Http_Headers::movedTemporary( Mvc::getCurrentPageURI() );
		}


        Mvc::getCurrentPage()->addBreadcrumbNavigationData('Delete user');

		$this->view->setVar( 'user', $user );

		$this->render('classic/delete-confirm');
	}


}