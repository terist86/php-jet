<?php
/**
 *
 *
 *
 * Class describes one URL
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Sites
 */
namespace Jet;

interface Mvc_Site_LocalizedData_URL_Interface {


	/**
	 * @param string $site_ID
	 */
	public function setSiteID($site_ID);

	/**
	 * @return string
	 */
	public function getSiteID();


    /**
     * @param Locale $locale
     */
    public function setLocale( Locale $locale );

    /**
     * @return Locale
     */
    public function getLocale();


	/**
	 * @return string
	 */
	public function toString();

	/**
	 * @return string
	 */
	public function getAsNonSchemaURL();


	/**
	 * @return string
	 */
	public function getURL();

	/**
	 * @param string $URL
	 * @throws Mvc_Site_Exception
	 */
	public function setURL($URL);

	/**
	 * @return bool
	 */
	public function getIsDefault();

	/**
	 * @param bool $is_default
	 */
	public function setIsDefault($is_default);

	/**
	 * @return bool|string
	 */
	public function getSchemePart();

	/**
	 * @return bool|string
	 */
	public function getHostPart();

	/**
	 * @return bool|string
	 */
	public function getPostPart();

	/**
	 * @return bool|string
	 */
	public function getPathPart();

	/**
	 * @return bool
	 */
	public function getIsSSL();

	/**
	 * @param bool $is_SSL
	 */
	public function setIsSSL( $is_SSL );

}