<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class BaseObject_Trait_Signals
 * @package Jet
 */
trait BaseObject_Trait_Signals {

    /**
     * @param $signal_name
     *
     * @return bool
     */
    public function getHasSignal( $signal_name ) {
        $signals = BaseObject_Reflection::get( get_class($this), 'signals', []);

        return in_array( $signal_name, $signals );
    }

    /**
     *
     * @param string $signal_name
     *
     * @return string
     */
    public function getSignalObjectClassName(
        /** @noinspection PhpUnusedParameterInspection */
        $signal_name
    ) {

        return BaseObject_Reflection::get( get_class($this), 'signal_object_class_name', __NAMESPACE__.'\\'.Application_Signals::DEFAULT_SIGNAL_OBJECT_CLASS_NAME );
    }

    /**
     * @param $signal_name
     * @param array $signal_data
     *
     * @throws BaseObject_Exception
     *
     * @return Application_Signals_Signal
     */
    public function sendSignal( $signal_name, array $signal_data= []) {

        /** @var $this BaseObject_Interface */
        $signal = Application_Signals::createSignal( $this, $signal_name, $signal_data );

        Application_Signals_Dispatcher::dispatchSignal( $signal );

        return $signal;
    }

}