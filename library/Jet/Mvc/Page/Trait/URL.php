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
trait Mvc_Page_Trait_URL
{

	/**
	 * @param string|null|bool $schema
	 * @param array            $path_fragments
	 * @param array            $GET_params
	 *
	 * @return string
	 */
	protected function _createURL( $schema, array $path_fragments, array $GET_params )
	{
		/**
		 * @var Mvc_Page|Mvc_Page_Trait_URL $this
		 */

		$site = $this->getSite();

		$URL = $site->getDefaultURL($this->locale).$this->relative_path;

		if($schema===false) {
			$URL = strchr($URL, '/');
		} else {
			if($schema===null) {
				if($this->getSSLRequired()) {
					$schema = 'https';
				} else {
					$schema = 'http';
				}
			}

			if(!$schema) {
				$URL = '//'.$URL;

			} else {
				$URL = $schema.'://'.$URL;
			}
		}


		if( $path_fragments ) {

			$do_not_add_slash = false;
			$p = '';
			foreach( $path_fragments as $i => $p ) {
				$path_fragments[$i] = rawurlencode( $p );
			}


			if(strpos($p, '.')!==false) {
				$do_not_add_slash = true;
			}

			$path_fragments = implode( '/', $path_fragments );

			$URL .= '/'.$path_fragments;

			if(
				Mvc::getForceSlashOnURLEnd() &&
				!$do_not_add_slash
			) {
				$URL .= '/';
			}
		} else {
			if(
				$this->relative_path &&
				Mvc::getForceSlashOnURLEnd()
			) {
				$URL .= '/';
			}

		}

		if( $GET_params ) {
			foreach( $GET_params as $k => $v ) {
				if( is_object( $v ) ) {
					$GET_params[$k] = (string)$v;
				}
			}

			$query = http_build_query( $GET_params );

			$URL .= '?'.$query;
		}

		return $URL;

	}


	/**
	 *
	 * @param array $path_fragments
	 * @param array $GET_params
	 *
	 * @return string
	 */
	public function getURL( array $path_fragments = [], array $GET_params = [] )
	{
		return $this->_createURL( null, $path_fragments, $GET_params );
	}

	/**
	 *
	 * @param array $path_fragments
	 * @param array $GET_params
	 *
	 * @return string
	 */
	public function getURI( array $path_fragments = [], array $GET_params = [] )
	{
		return $this->_createURL( false, $path_fragments, $GET_params );
	}


	/**
	 *
	 * @param array $path_fragments
	 * @param array $GET_params
	 *
	 * @return string
	 */
	public function getNonSchemaURL( array $path_fragments = [], array $GET_params = [] )
	{
		/**
		 * @var Mvc_Page|Mvc_Page_Trait_URL $this
		 */

		return $this->_createURL( '', $path_fragments, $GET_params );
	}

	/**
	 *
	 * @param array $path_fragments
	 * @param array $GET_params
	 *
	 * @return string
	 */
	public function getNonSslURL( array $path_fragments = [], array $GET_params = [] )
	{
		return $this->_createURL( 'http', $path_fragments, $GET_params );
	}

	/**
	 *
	 * @param array $path_fragments
	 * @param array $GET_params
	 *
	 * @return string
	 */
	public function getSslURL( array $path_fragments = [], array $GET_params = [] )
	{
		return $this->_createURL( 'https', $path_fragments, $GET_params );
	}

}