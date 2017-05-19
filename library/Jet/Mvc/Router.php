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
 */
class Mvc_Router extends BaseObject  implements Mvc_Router_Interface
{
	/**
	 * @var bool
	 */
	protected $set_mvc_state = true;

	/**
	 * @var callable
	 */
	protected $after_site_resolved;

	/**
	 * @var callable
	 */
	protected $after_page_resolved;

	/**
	 *
	 * @var string
	 */
	protected $request_URL = '';

	/**
	 * @var Mvc_Site_Interface
	 */
	protected $site;

	/**
	 * @var Locale
	 */
	protected $locale;

	/**
	 * @var Mvc_Page
	 */
	protected $page;

	/**
	 *
	 */
	protected $path = '';


	//------------------------------------------------------------------

	/**
	 * @var string
	 */
	protected $file_path = '';

	//------------------------------------------------------------------
	/**
	 * @var bool
	 */
	protected $is_404 = false;

	//------------------------------------------------------------------

	/**
	 * @see Mvc_Router_RoutingData::setRedirect()
	 *
	 * @var bool
	 */
	protected $is_redirect = false;

	/**
	 * @see Mvc_Router_RoutingData::setRedirect()
	 *
	 * @var string
	 */
	protected $redirect_target_URL = '';

	/**
	 * @see Mvc_Router_RoutingData::setRedirect()
	 * Options: Mvc_Router::REDIRECT_TYPE_TEMPORARY, Mvc_Router::REDIRECT_TYPE_PERMANENTLY
	 *
	 * @var string
	 */
	protected $redirect_type = '';


	//-----------------------------------------------------------------

	/**
	 * @var bool
	 */
	protected $login_required = false;

	/**
	 * @param callable $after_site_resolved
	 */
	public function afterSiteResolved( callable $after_site_resolved )
	{
		$this->after_site_resolved = $after_site_resolved;
	}

	/**
	 * @param callable $after_page_resolved
	 */
	public function afterPageResolved( callable $after_page_resolved )
	{
		$this->after_page_resolved = $after_page_resolved;
	}

	/**
	 * @return bool
	 */
	public function getIsSSLRequest()
	{
		return Http_Request::getRequestIsHttps();
	}

	/**
	 *
	 *
	 * @param string $request_URL
	 *
	 * @return void
	 *
	 * @throws Mvc_Router_Exception
	 */
	public function resolve( $request_URL=null )
	{
		Debug_Profiler::blockStart('main init');

		if( !$request_URL ) {
			$request_URL = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		}

		if(($pos=strpos($request_URL, '?'))!==false) {
			$request_URL = substr($request_URL, 0, $pos);
		}

		if(($pos=strpos($request_URL, '://'))!==false) {
			$request_URL = substr($request_URL, $pos+2);

		}
		$this->request_URL = $request_URL;

		Debug_Profiler::blockEnd('main init');

		if( $this->validateURIFormat() ) {

			if( $this->resolveSiteAndLocale() ) {

				if($this->after_site_resolved) {
					$after = $this->after_site_resolved;
					$after( $this );
				}

				if( $this->resolvePage() ) {
					$this->resolveAuthentication();

					if($this->after_page_resolved) {
						$after = $this->after_page_resolved;
						$after( $this );
					}
				}

			}
		}

	}

	/**
	 *
	 * @return bool
	 */
	protected function validateURIFormat()
	{


		if(Mvc::getForceSlashOnURLEnd()) {
			$last = strrchr($this->request_URL, '/');

			if($last=='/') {
				$this->request_URL = substr($this->request_URL, 0, -1);
				return true;
			}

			if( strpos( $last, '.' )!==false ) {
				return true;
			}

			$redirect_to = Http_Request::getRequestIsHttps() ? 'https://' : 'http://';
			$redirect_to .= $this->request_URL.'/';

		} else {
			if(substr_count($this->request_URL, '/')==1) {

				$last = strrchr($this->request_URL, '/');

				if($last=='/') {
					$this->request_URL = substr($this->request_URL, 0, -1);
				}

				return true;
			}

			$last = strrchr($this->request_URL, '/');

			if($last!='/') {
				return true;
			}

			$redirect_to = Http_Request::getRequestIsHttps() ? 'https://' : 'http://';
			$redirect_to .= substr($this->request_URL,0,-1);
		}


		$this->setIsRedirect(
			$redirect_to,
			Http_Headers::CODE_301_MOVED_PERMANENTLY
		);

		return false;

	}

	/**
	 * @return bool
	 */
	protected function resolveSiteAndLocale()
	{

		Debug_Profiler::blockStart('Resolve site and locale');

		/**
		 * @var Mvc_Site_Interface $site_class_name
		 */
		$site_class_name = Mvc_Factory::getSiteInstance();

		Debug_Profiler::blockStart('Load sites');
		$site_class_name::loadSites();
		Debug_Profiler::blockEnd('Load sites');


		Debug_Profiler::blockStart('Seeking for site');
		$site_URLs_map = $site_class_name::getUrlMap();

		$known_URLs = array_keys( $site_URLs_map );

		usort(
			$known_URLs,
			function( $a, $b ) {
				return strlen( $b )-strlen( $a );
			}
		);


		$current_site_URL = null;


		$founded_url = null;

		foreach( $known_URLs as $URL ) {

			if(substr($this->request_URL.'/', 0, strlen($URL))==$URL) {
				$d = $site_URLs_map[$URL];
				$this->site = $d->getSite();
				$this->locale = $d->getLocale();

				$founded_url = $URL;

				$this->path = substr($this->request_URL, strlen($founded_url));
				if(!$this->path) {
					$this->path = '';
				}

				break;
			}
		}

		Debug_Profiler::blockEnd('Seeking for site');

		$OK = true;
		if( !$this->site ) {
			$this->setIs404();

			Debug_Profiler::message('site not found');
			$OK = false;
		} else {

			if($this->set_mvc_state) {
				Mvc::setCurrentSite($this->site);
				Mvc::setCurrentLocale($this->locale);
			}

			if( $founded_url!=$this->site->getDefaultURL($this->locale) ) {

				$redirect_to = $this->getSite()->getDefaultURL( $this->locale ).$this->path;

				if($this->path && Mvc::getForceSlashOnURLEnd()) {
					$redirect_to .= '/';
				}

				$this->setIsRedirect( $redirect_to );

				Debug_Profiler::message('wrong site URL');

				$OK = false;
			}

		}



		Debug_Profiler::blockEnd('Resolve site and locale');
		return $OK;
	}

	/**
	 * @return bool
	 *
	 * @throws Mvc_Router_Exception
	 */
	protected function resolvePage()
	{
		Debug_Profiler::blockStart('Resolve page');

		/**
		 * @var Mvc_Page_Interface $page_class_name
		 */
		$page_class_name = Mvc_Factory::getPageClassName();


		Debug_Profiler::blockStart('Load pages');
		$page_class_name::loadPages( $this->getSite(), $this->getLocale() );
		Debug_Profiler::blockEnd('Load pages');


		Debug_Profiler::blockStart('Seeking for page');
		$relative_URIs = [];

		if($this->path) {
			$path = explode('/', $this->path);

			while($path) {
				$relative_URIs[] = implode( '/', $path );
				unset( $path[count( $path )-1] );
			}
		}



		foreach( $relative_URIs as $i => $URI ) {

			$this->page = $page_class_name::getByRelativePath( $this->getSite(), $this->getLocale(), $URI );

			if( $this->page ) {

				$this->path = substr($this->path, strlen($URI)+1);
				if(!$this->path) {
					$this->path = '';
				}

				break;
			}
		}


		if( !$this->page ) {
			$this->page = $this->site->getHomepage($this->locale);
		}

		if($this->set_mvc_state) {
			Mvc::setCurrentPage($this->page);
		}

		Debug_Profiler::blockEnd('Seeking for page');


		$this->path = rawurldecode($this->path);

		$path = [];
		if($this->path) {
			$path = explode('/', $this->path);
		}

		$page_url = $this->page->getURL( $path );


		$OK = $page_url==Http_Request::getURL(false);


		if(!$OK) {
			$this->setIsRedirect( $page_url );
		} else {
			if( $this->path ) {
				Debug_Profiler::blockStart('Parsing path');
				$OK = $this->getPage()->parseRequestPath();
				Debug_Profiler::blockEnd('Parsing path');

				if( !$OK ) {
					$this->setIs404();
				}
			}

		}


		Debug_Profiler::blockEnd('Resolve page');
		return $OK;

	}

	/**
	 *
	 * @throws Mvc_Router_Exception
	 * @return bool
	 */
	protected function resolveAuthentication()
	{

		if(
			!$this->getPage()->getIsAdminUI() &&
			!$this->getPage()->getIsSecretPage()
		) {
			return true;
		}

		if( Auth::isUserLoggedIn() ) {
			return true;
		}

		$this->login_required = true;

		return false;

	}

	/**
	 * @return bool
	 */
	public function getSetMvcState()
	{
		return $this->set_mvc_state;
	}

	/**
	 * @param bool $set_mvc_state
	 */
	public function setSetMvcState( $set_mvc_state )
	{
		$this->set_mvc_state = $set_mvc_state;
	}

	/**
	 *
	 * @return Mvc_Site_Interface
	 */
	public function getSite()
	{
		return $this->site;
	}

	/**
	 * @return Locale
	 */
	public function getLocale()
	{
		return $this->locale;
	}

	/**
	 *
	 * @return Mvc_Page_Interface
	 */
	public function getPage()
	{
		return $this->page;
	}

	/**
	 * @param string $file_path
	 */
	public function setIsFile( $file_path )
	{
		$this->file_path = $file_path;
	}

	/**
	 *
	 * @return bool
	 */
	public function getIsFile()
	{
		return (bool)$this->file_path;
	}

	/**
	 * @return string
	 */
	public function getFilePath()
	{
		return $this->file_path;
	}

	/**
	 * Returns true is request is unknown page
	 *
	 * @return bool
	 */
	public function getIs404()
	{
		return $this->is_404;
	}

	/**
	 * Sets the request is unknown page
	 *
	 */
	protected function setIs404()
	{
		$this->is_404 = true;
	}

	/**
	 * @return bool
	 */
	public function getIsRedirect()
	{
		return $this->is_redirect;
	}

	/**
	 * Sets the redirect. Redirection is not performed immediately
	 *
	 * @param string $target_URL
	 * @param string $http_code (optional), options: temporary, permanent, default: Http_Headers::CODE_302_MOVED_TEMPORARY
	 */
	protected function setIsRedirect( $target_URL, $http_code = null )
	{
		if($_SERVER['QUERY_STRING']) {
			$target_URL .= '?'.$_SERVER['QUERY_STRING'];
		}

		if( !$http_code ) {
			$http_code = Http_Headers::CODE_302_MOVED_TEMPORARY;
		}

		$this->is_redirect = true;
		$this->redirect_target_URL = $target_URL;
		$this->redirect_type = $http_code;
	}

	/**
	 * @return string
	 */
	public function getRedirectTargetURL()
	{
		return $this->redirect_target_URL;
	}

	/**
	 * @return string
	 */
	public function getRedirectType()
	{
		return $this->redirect_type;
	}


	/**
	 * @return bool
	 */
	public function getLoginRequired()
	{
		return $this->login_required;
	}

	/**
	 * @param bool $login_required
	 */
	protected function setLoginRequired( $login_required )
	{
		$this->login_required = $login_required;
	}


	/**
	 *
	 * /**
	 * @return array
	 */
	public function getPath()
	{
		return $this->path;
	}


}