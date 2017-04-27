<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class BaseObject_Trait
 * @package Jet
 */
trait BaseObject_Trait {
    use BaseObject_Trait_Signals;
    use BaseObject_Trait_Properties;
}