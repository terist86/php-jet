<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Http_Request;
use Jet\Http_Headers;
use Jet\Mvc_Site;
use Jet\Mvc_Factory;
use Jet\Mvc_Site_Interface;
use Jet\Form;
use Jet\Form_Field_Input;

/**
 *
 */
class Installer_Step_CreateSite_Controller extends Installer_Step_Controller
{

	/**
	 * @var string
	 */
	protected $label = 'Create site';

	/**
	 *
	 */
	public function main()
	{

		if( count( Mvc_Site::loadSites() ) ) {
			$this->render( 'site-created' );

			$this->catchContinue();

			return;
		}

		$default_locale = Installer::getCurrentLocale();

		$session = Installer::getSession();

		if( !$session->getValueExists( 'sites' ) )
		{

			$URL = $_SERVER['HTTP_HOST'].JET_URI_BASE;

			$web = Mvc_Factory::getSiteInstance();
			$web->setName( 'Example Web' );
			$web->setId( Application::getWebSiteId() );

			$ld = $web->addLocale( $default_locale );
			$ld->setTitle('PHP Jet Example Web');
			$ld->setURLs( [$URL] );

			foreach( Installer::getSelectedLocales() as $locale ) {
				if( $locale->toString()==$default_locale->toString() ) {
					continue;
				}
				$ld = $web->addLocale( $locale );
				$ld->setTitle('PHP Jet Example Web');
				$ld->setURLs( [$URL.$locale->getLanguage()] );
			}
			$web->setIsDefault( true );
			$web->setIsActive( true );





			$admin = Mvc_Factory::getSiteInstance();
			$admin->setIsSecret();
			$admin->setName( 'Example Administration' );
			$admin->setId( Application::getAdminSiteId() );

			$ld = $admin->addLocale( $default_locale );
			$ld->setTitle('PHP Jet Example Administration');
			$ld->setURLs( [$URL.'admin/'] );

			foreach( Installer::getSelectedLocales() as $locale ) {
				if( $locale->toString()==$default_locale->toString() ) {
					continue;
				}
				$ld = $admin->addLocale( $locale );
				$ld->setTitle('PHP Jet Example Web');
				$ld->setURLs( [$URL.'admin/'.$locale->getLanguage().'/'] );
			}
			$admin->setIsActive( true );



			$rest = Mvc_Factory::getSiteInstance();
			$rest->setIsSecret();
			$rest->setName( 'Example REST API' );
			$rest->setId( Application::getRESTSiteId() );

			$ld = $rest->addLocale( $default_locale );
			$ld->setTitle('PHP Jet Example REST API');
			$ld->setURLs( [$URL.'rest/'] );

			foreach( Installer::getSelectedLocales() as $locale ) {
				if( $locale->toString()==$default_locale->toString() ) {
					continue;
				}
				$ld = $rest->addLocale( $locale );
				$ld->setTitle('PHP Jet Example Web');
				$ld->setURLs( [$URL.'rest/'.$locale->getLanguage().'/'] );
			}
			$rest->setIsActive( true );



			$sites = [
				$web->getId()   => $web,
			    $admin->getId() => $admin,
			    $rest->getId()  => $rest
			];


			$session->setValue( 'sites', $sites );

		}
		else {
			$sites = $session->getValue( 'sites' );
		}

		/**
		 * @var Mvc_Site_Interface $site
		 */


		if(
			Http_Request::GET()->exists( 'create' ) &&
			count( $sites )
		) {
			if( !$session->getValue( 'creating' ) ) {
				$session->setValue( 'creating', true );
				$this->render( 'in-progress' );

			} else {
				foreach( $sites as $site ) {
					$site->saveDataFile();
				}


				Http_Headers::movedPermanently( '?' );
			}

		}


		//----------------------------------------------------------------------
		$main_form_fields = [];

		foreach( $sites as $site) {
			foreach( $site->getLocales() as $locale ) {
				$URL = $site->getLocalizedData( $locale )->getURLs()[0];

				$URL = rtrim( $URL, '/' );

				$URL_field = new Form_Field_Input( '/'.$site->getId().'/'.$locale.'/URL', 'URL ', $URL, true );

				$URL_field->setErrorMessages(
					[
						Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter URL',
					]
				);

				$main_form_fields[] = $URL_field;
			}

		}


		$main_form = new Form( 'main', $main_form_fields );

		if(
			$main_form->catchInput() &&
			$main_form->validate()
		) {

			foreach( $sites as $site ) {

				foreach( $site->getLocales() as $locale ) {
					$URL = strtolower($main_form->getField('/'.$site->getId().'/'.$locale.'/URL')->getValue());
					$URL = rtrim($URL, '/');

					$URL = str_replace('http://', '', $URL);
					$URL = str_replace('https://', '', $URL);
					$URL = str_replace('//', '', $URL);

					$site->getLocalizedData( $locale )->setURLs([$URL]);
				}
			}


			Http_Headers::movedPermanently( '?create' );

		}


		//----------------------------------------------------------------------

		$this->view->setVar( 'sites', $sites );
		$this->view->setVar( 'main_form', $main_form );

		$this->render( 'default' );
	}

}
