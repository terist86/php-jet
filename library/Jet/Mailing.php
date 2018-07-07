<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Mailing extends BaseObject
{
	/**
	 * @var Mailing_Config
	 */
	protected static $config = null;

	/**
	 * @var Mailing_Backend_Abstract
	 */
	protected static $backend;

	/**
	 * @var string
	 */
	protected static $base_view_dir = JET_PATH_APPLICATION.'views/email_templates/';


	/**
	 * @return string
	 */
	public static function getBaseViewDir()
	{
		return static::$base_view_dir;
	}

	/**
	 * @param string $dir
	 */
	public static function setBaseViewDir( $dir )
	{
		static::$base_view_dir = $dir;
	}

	/**
	 *
	 * @param bool $soft_mode
	 *
	 * @return Mailing_Config
	 */
	public static function getConfig( $soft_mode=false )
	{
		if( !static::$config ) {
			static::$config = new Mailing_Config( $soft_mode );
		}

		return static::$config;
	}

	/**
	 * @return Mailing_Backend_Abstract
	 */
	public static function getBackend()
	{
		if( !static::$backend ) {
			static::$backend = new Mailing_Backend_Default();
		}

		return static::$backend;
	}

	/**
	 * @param Mailing_Backend_Abstract $backend
	 */
	public static function setBackend( Mailing_Backend_Abstract $backend )
	{
		static::$backend = $backend;
	}
	
	

	/**
	 * @param Mailing_Email $email
	 * @param string $to
	 * @param array $headers
	 *
	 * @return bool
	 */
	public static function sendEmail( Mailing_Email $email, $to, array $headers=[] )
	{
		return static::getBackend()->sendEmail( $email, $to, $headers );
	}


}



