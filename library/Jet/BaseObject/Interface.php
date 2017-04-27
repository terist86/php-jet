<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

//We don't have multiple inheritance in PHP :-(
/**
 * Interface BaseObject_Interface
 * @package Jet
 */
interface BaseObject_Interface {


	/**
	 * Default serialize rules (don't serialize __* properties)
	 *
	 * @return array
	 */
	public function __sleep();

	/**
	 * Getter for protected properties
	 *
	 * @param string $key
	 * @throws BaseObject_Exception
	 *
	 */
	public function __get( $key );

	/**
	 * Setter for protected properties
	 *
	 * @param string $key
	 * @param mixed $value
	 * @throws BaseObject_Exception
	 */
	public function __set( $key, $value );

	/**
	 *
	 */
	public function __clone();

	/**
	 * @param $signal_name
	 *
	 * @return bool
	 */
	public function getHasSignal( $signal_name );

	/**
	 * @param string $signal_name
	 *
	 * @return string
	 */
	public function getSignalObjectClassName( $signal_name );

	/**
	 * @param $signal_name
	 * @param array $signal_data
	 *
	 * @throws BaseObject_Exception
	 *
	 * @return Application_Signals_Signal
	 */
	public function sendSignal( $signal_name, array $signal_data= []);


	/**
	 * @return string
	 */
	public function getObjectClassNamespace();

	/**
	 * @param $property_name
	 *
	 * @return bool
	 */
	public function getObjectClassHasProperty($property_name );

	/**
	 * @param $property_name
	 *
	 * @return string
	 */
	public function getSetterMethodName( $property_name );


	/**
	 * @param $property_name
	 *
	 * @return string
	 */
	public function getGetterMethodName( $property_name );

}