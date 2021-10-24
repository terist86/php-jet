<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
interface Mvc_Base_LocalizedData_Interface
{

	/**
	 * @param Mvc_Base_Interface $base
	 * @param Locale $locale
	 * @param array $data
	 *
	 * @return Mvc_Base_LocalizedData_Interface
	 */
	public static function createByData( Mvc_Base_Interface $base, Locale $locale, array $data ) : Mvc_Base_LocalizedData_Interface;

	/**
	 * @return Mvc_Base_Interface
	 */
	public function getBase(): Mvc_Base_Interface;

	/**
	 * @param Mvc_Base_Interface $base
	 */
	public function setBase( Mvc_Base_Interface $base ): void;


	/**
	 * @return Locale
	 */
	public function getLocale(): Locale;

	/**
	 * @param Locale $locale
	 *
	 */
	public function setLocale( Locale $locale ): void;

	/**
	 * @return bool
	 */
	public function getIsActive(): bool;

	/**
	 * @param bool $is_active
	 */
	public function setIsActive( bool $is_active ): void;


	/**
	 * @return string
	 */
	public function getTitle(): string;

	/**
	 * @param string $title
	 */
	public function setTitle( string $title ): void;

	/**
	 * @return array
	 */
	public function getURLs(): array;

	/**
	 * @param array $URLs
	 */
	public function setURLs( array $URLs ): void;

	/**
	 * @return string
	 */
	public function getDefaultURL(): string;

	/**
	 * @return bool
	 */
	public function getSSLRequired(): bool;

	/**
	 * @param bool $SSL_required
	 */
	public function setSSLRequired( bool $SSL_required ): void;


	/**
	 *
	 * @return Mvc_Base_LocalizedData_MetaTag_Interface[]
	 */
	public function getDefaultMetaTags(): array;

	/**
	 *
	 * @param Mvc_Base_LocalizedData_MetaTag_Interface $default_meta_tag
	 */
	public function addDefaultMetaTag( Mvc_Base_LocalizedData_MetaTag_Interface $default_meta_tag ): void;

	/**
	 *
	 * @param int $index
	 */
	public function removeDefaultMetaTag( int $index ): void;

	/**
	 *
	 * @param Mvc_Base_LocalizedData_MetaTag_Interface[] $default_meta_tags
	 */
	public function setDefaultMetaTags( array $default_meta_tags ): void;


	/**
	 * @return array
	 */
	public function toArray(): array;

}