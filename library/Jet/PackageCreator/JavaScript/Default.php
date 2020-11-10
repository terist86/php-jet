<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class PackageCreator_JavaScript_Default extends PackageCreator_JavaScript
{

	/**
	 * @var string
	 */
	protected $key = null;

	/**
	 *
	 * @param array  $URIs
	 */
	public function __construct( array $URIs )
	{
		$this->URIs = $URIs;
	}

	/**
	 *
	 */
	public function generate()
	{

		$package_path = $this->getPackagePath();

		if(
			!IO_File::exists( $package_path )
		) {

			IO_File::write(
				$package_path, $this->createPackage()
			);
		}
	}


	/**
	 *
	 * @return string
	 */
	public function createPackage()
	{
		$JS = '';

		foreach( $this->URIs as $URI ) {
			$JS .= '/* URI: '.$URI.' */'.PHP_EOL;
			$JS .= $this->getFileContent( $URI ).PHP_EOL;
			$JS .= '/* ------------------------ */ '.PHP_EOL;
		}

		return $JS;
	}


	/**
	 *
	 * @return string
	 */
	public function getKey()
	{
		if( !$this->key ) {
			$this->key = md5( implode( '', $this->URIs ) );
		}

		return $this->key;
	}


	/**
	 * @return string
	 */
	public function getPackageRelativeFileName()
	{

		return static::getPackagesDirName().'/'.$this->getKey().'.js';
	}

	/**
	 * @return string
	 */
	public function getPackagePath()
	{
		return SysConf_PATH::PUBLIC().$this->getPackageRelativeFileName();
	}

	/**
	 * @return string
	 */
	public function getPackageURI()
	{
		return SysConf_URI::PUBLIC().$this->getPackageRelativeFileName();
	}

}